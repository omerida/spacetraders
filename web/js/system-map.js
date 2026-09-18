// A simple object to represent a label
function WaypointLabel(x, y, value) {
    this.x = x;
    this.y = y;
    this.value = value;
}

// Function to calculate the Euclidean distance between
// the top-left corners of two labels
function getDistance(label1, label2) {
    const dx = label1.x - label2.x;
    const dy = label1.y - label2.y;
    return Math.sqrt(dx * dx + dy * dy);
}

function drawMap(waypoints) {
    // 1600 for coordinates + 50px padding on each side
    const canvas_width = 1700;
    const canvas_height = 1600;

    const stage = new Konva.Stage({
        container: 'system-map', // id of container <div>
        width: canvas_width,
        height: canvas_height,
        draggable: true,
        fill: 'black',
        x: -300,
        y: -600,
        scaleX: 1.2,
        scaleY: 1.2
    });

    const scaleBy = 1.1; // How much to zoom on each step
    const lodZoomThreshold = 2.8; // Zoom level at which orbitals appear

    // Helper to toggle visibility of satellite objects
    const updateLOD = (currentScale) => {
        const isZoomedIn = currentScale >= lodZoomThreshold;
        pointsLayer.find('.waypointGroup').forEach(group => {
            if (group.getAttr('isOrbital')) {
                group.visible(isZoomedIn);
            }
        });
    };

    // Adding zoom on scroll-wheel
    stage.on('wheel', (e) => {
      // 1. Prevent default scroll
      e.evt.preventDefault();

      const stage = e.target.getStage();
      const oldScale = stage.scaleX();
      const pointer = stage.getPointerPosition();

      // 3. Find the point-to-zoom (relative to the unscaled stage)
      const mousePointTo = {
        x: (pointer.x - stage.x()) / oldScale,
        y: (pointer.y - stage.y()) / oldScale,
      };

      // 2. Determine zoom direction and calculate new scale
      let direction = e.evt.deltaY > 0 ? -1 : 1;
      const newScale = direction > 0 ? oldScale * scaleBy : oldScale / scaleBy;

      // Set the new scale
      stage.scale({ x: newScale, y: newScale });

      // 4. Calculate new position to keep the zoom point stationary
      const newPos = {
        x: pointer.x - mousePointTo.x * newScale,
        y: pointer.y - mousePointTo.y * newScale,
      };

      // Set the new position
      stage.position(newPos);

      // --- Label Size Compensation Logic ---
      const inverseScale = 1 / newScale;
      pointsLayer.find('.waypointGroup').forEach(group => {
         group.scale({ x: inverseScale, y: inverseScale });
      });

      // --- Level of Detail Logic ---
      updateLOD(newScale);

      stage.batchDraw(); // Redraw the stage efficiently
    });

    const background = new Konva.Layer();
    background.add(new Konva.Rect({
        x: 0,
        y: 0,
        width: stage.width(),
        height: stage.height(),
        fill: '#000'
    }));

    // Decorate with stars
    const numStars = 400;
    const minOpacity = 0.4;
    const maxOpacity = 0.7;

    for (let i = 0; i < numStars; i++) {
        const star = new Konva.Circle({
            x: Math.random() * canvas_width,
            y: Math.random() * canvas_height,
            radius: 1.1,
            fill: '#ccc',
            opacity: minOpacity + (Math.random() * (maxOpacity - minOpacity))
        })
        background.add(star)
    }

    stage.add(background);

    const grid = new Konva.Layer();

    for (let i = 50; i < stage.width(); i = (i + 100)) {
        grid.add(new Konva.Line({
            points: [i, 0, i, stage.height()],
            stroke: '#777',
            strokeWidth: 1,
            dash: [6, 6]
        }))

        grid.add(new Konva.Line({
            points: [0, i + 50 , stage.width(), i + 50],
            stroke: '#777',
            strokeWidth: 1,
            dash: [6, 6]
        }))
    }

    grid.add(new Konva.Line({
        points: [(stage.width() / 2), 0, (stage.width() / 2), stage.height()],
        stroke: '#999',
        strokeWidth: 2,
    }))

    grid.add(new Konva.Line({
        points: [0, (stage.height() / 2), stage.width(), (stage.height() / 2)],
        stroke: '#999',
        strokeWidth: 2,
    }))
    stage.add(grid)

    console.log(waypoints)
    const pointsLayer = new Konva.Layer();
    for (let i = 0; i < waypoints.length; i++) {
        const waypointGroup = getMapSymbol(waypoints[i], waypoints);
        pointsLayer.add(waypointGroup);
    }
    stage.add(pointsLayer);

    // Initial check on load based on starting scale (1.2)
    updateLOD(stage.scaleX());
}

function getMapSymbol(point, allWaypoints = [], customX = null, customY = null) {
    const padding = 50;

    const xCoord = customX !== null ? customX : point.x;
    const yCoord = customY !== null ? customY : point.y;

    const map_x = (xCoord + 800) + padding;
    const map_y = (1600 - (yCoord + 800)) + padding;

    let width = 25;
    let height = 25;
    let imgSrc = '/assets/uncertainty.svg';

    switch (point.type) {
        case 'PLANET':
            imgSrc = '/assets/world.svg';
            width = 40; height = 30;
            break;
        case 'GAS_GIANT':
            imgSrc = '/assets/gas-giant.svg';
            width = 35; height = 35;
            break;
        case 'MOON':
            imgSrc = '/assets/moon.svg';
            width = 20; height = 20;
            break;
        case 'ENGINEERED_ASTEROID': imgSrc = '/assets/asteroid-red.svg'; break;
        case 'JUMP_GATE': imgSrc = '/assets/warp-gate.svg'; break;
        case 'FUEL_STATION': imgSrc = '/assets/apollo-capsule-blue.svg'; break;
        case 'ORBITAL_STATION': imgSrc = '/assets/orbital-station.svg'; break;
        case 'ASTEROID': imgSrc = '/assets/asteroid-brown.svg'; break;
        case 'ASTEROID_BASE': imgSrc = '/assets/asteroid-blue.svg'; break;
    }

    const waypointGroup = new Konva.Group({
        x: map_x,
        y: map_y,
        name: 'waypointGroup',
        isOrbital: !!point.orbits
    });

    const imageObj = new Image();
    imageObj.src = imgSrc;

    const symbol = new Konva.Image({
        height: height,
        width: width,
        image: imageObj,
        offsetX: width / 2,
        offsetY: height / 2
    });

    imageObj.onload = () => {
        symbol.getLayer()?.batchDraw();
    };

    waypointGroup.add(symbol);

    // Label setup
    const regex = /[^-]+$/;
    const label = new Konva.Label();

    // --- Dynamic Label Styling & Positioning ---
    let parentWp = null;
    if (point.orbits && allWaypoints.length > 0) {
        parentWp = allWaypoints.find(wp => {
            const sym = typeof wp.symbol === 'object' ? wp.symbol.waypoint : wp.symbol;
            return sym === point.orbits;
        });
    }

    const symbolText = point.symbol.waypoint ? point.symbol.waypoint.match(regex)[0] : point.symbol.match(regex)[0];

    // Style configuration based on orbital status
    const tagFill = parentWp ? 'grey' : 'white';
    const textFill = parentWp ? 'green' : 'black';
    const tagOpacity = parentWp ? 0.25 : 0.85; // Raised opacity for white tags so grid lines don't show through

    label.add(new Konva.Tag({
        fill: tagFill,
        opacity: tagOpacity,
        cornerRadius: 4
    }));

    const textNode = new Konva.Text({
        text: symbolText,
        fontSize: 12,
        padding: 5,
        fill: textFill,
        align: 'center'
    });

    label.add(textNode);

    // --- Dynamic Label Positioning ---
    if (point.orbits && allWaypoints.length > 0) {
        parentWp = allWaypoints.find(wp => {
            const sym = typeof wp.symbol === 'object' ? wp.symbol.waypoint : wp.symbol;
            return sym === point.orbits;
        });
    }

    if (parentWp) {
        // Position side-by-side for satellites
        const isRightOfParent = point.x >= parentWp.x;
        const xOffset = (width / 2) + 6;

        if (isRightOfParent) {
            label.position({ x: xOffset, y: -textNode.height() / 2 });
            label.offsetX(0); // Left-aligned relative to label start
        } else {
            label.position({ x: -xOffset, y: -textNode.height() / 2 });
            label.offsetX(textNode.width()); // Right-aligned relative to label start
        }
    } else {
        // Standard position (below) for central bodies / non-orbitals
        label.position({ x: 0, y: (height / 2) + 5 });
        label.offsetX(textNode.width() / 2);
    }

    waypointGroup.add(label);

    waypointGroup.on('click', () => {
        const symbolStr = typeof point.symbol === 'object' ? point.symbol.waypoint : point.symbol;
        showDrawer(symbolStr);
    });

    return waypointGroup;
}

// Function to compute derived positions for orbital waypoints
function calculateOrbitalPositions(waypoints, orbitalRadius = 8) {
  // Map waypoints by symbol string/object symbol for quick lookup
  const waypointMap = new Map();
  waypoints.forEach(wp => {
    const symbolStr = typeof wp.symbol === 'object' ? wp.symbol.waypoint : wp.symbol;
    waypointMap.set(symbolStr, { ...wp });
  });

  // Calculate coordinates for child orbitals
  waypoints.forEach(wp => {
    if (wp.orbitals && wp.orbitals.length > 0) {
      const parentSymbol = typeof wp.symbol === 'object' ? wp.symbol.waypoint : wp.symbol;
      const numOrbitals = wp.orbitals.length;

      wp.orbitals.forEach((orbitalRef, index) => {
        const orbitalSymbol = typeof orbitalRef === 'string' ? orbitalRef : orbitalRef.symbol;
        const orbitalNode = waypointMap.get(orbitalSymbol);

        if (orbitalNode) {
          const angle = (2 * Math.PI * index) / numOrbitals;
          orbitalNode.x = wp.x + Math.round(orbitalRadius * Math.cos(angle));
          orbitalNode.y = wp.y + Math.round(orbitalRadius * Math.sin(angle));
          orbitalNode.orbits = parentSymbol; // Store parent relation
        }
      });
    }
  });

  return Array.from(waypointMap.values());
}

function getMapData(systemID) {

    fetch('/systems/waypoint/map/json?system=' + systemID)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Or .text() for plain text
        })
        .then(data => {
            // Transform raw payload coordinates before drawing
            const processedData = calculateOrbitalPositions(data, 10);
            drawMap(processedData);
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}

function renderSystemMap(systemID) {
    if (systemID) {
        getMapData(systemID);
    }
}

function showDrawer(waypoint) {
    up.layer.open({
        url: '/systems/waypoint?id=' + waypoint,
        target: '.content',
        layer: 'swap',
        mode: 'drawer',
        size: 'large'
    })
}
