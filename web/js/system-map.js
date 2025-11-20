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

function simulatedAnnealing(initialLabels, minDistance) {
    let labels = initialLabels.slice(); // Use a copy to avoid modifying the original
    let currentEnergy = calculateEnergy(labels, minDistance);
    let bestLabels = labels.slice();
    let bestEnergy = currentEnergy;

    // Parameters for the algorithm
    const initialTemperature = 10000;
    const coolingRate = 0.995;
    const minTemperature = 1;

    let temperature = initialTemperature;

    while (temperature > minTemperature) {
        // If we have an optimal solution (no distance violations), stop
        if (currentEnergy === 0) {
            break;
        }

        const randomIndex = Math.floor(Math.random() * labels.length);
        const originalLabel = labels[randomIndex];
        const newLabel = new WaypointLabel(
            originalLabel.x,
            originalLabel.y,
            originalLabel.value
        );

        // Perturb the label's position
        const maxMoveDistance = 10;
        newLabel.x += (Math.random() - 0.5) * maxMoveDistance;
        newLabel.y += (Math.random() - 0.5) * maxMoveDistance;

        // Calculate the new energy with the minimum distance
        const newLabels = labels.slice();
        newLabels[randomIndex] = newLabel;
        const newEnergy = calculateEnergy(newLabels, minDistance);
        const energyChange = newEnergy - currentEnergy;

        // The core decision: accept the new state?
        if (energyChange < 0 || Math.random() < Math.exp(-energyChange / temperature)) {
            labels = newLabels;
            currentEnergy = newEnergy;

            if (currentEnergy < bestEnergy) {
                bestEnergy = currentEnergy;
                bestLabels = labels.slice();
            }
        }

        temperature *= coolingRate;
    }

    return bestLabels;
}

// Calculate the total energy (sum of all penalties for violating
// the minimum distance)
function calculateEnergy(labels, minDistance) {
    let totalPenalty = 0;
    for (let i = 0; i < labels.length; i++) {
        for (let j = i + 1; j < labels.length; j++) {
            const distance = getDistance(labels[i], labels[j]);
            // If the distance is less than the minimum threshold, add a penalty
            if (distance < minDistance) {
                const penalty = (minDistance - distance) * (minDistance - distance); // Square the difference for a steeper penalty
                totalPenalty += penalty;
            }
        }
    }
    return totalPenalty;
}

function drawMap(waypoints) {
    // 1600 for cordinates + 50px padding on each side
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
      pointsLayer.find('Text').forEach(textNode => {
        textNode.scale({ x: inverseScale, y: inverseScale });
      });

      pointsLayer.find('Image').forEach((image) => {
        image.scale({ x: inverseScale, y: inverseScale });
      });

      pointsLayer.find('Tag').forEach((tag) => {
        tag.scale({ x: inverseScale, y: inverseScale });
      });


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
   const numStars = 300;
    const minOpacity = 0.4;
    const maxOpacity = 0.7;

    for (let i =0; i < numStars; i++) {
        const star = new Konva.Circle({
            x: Math.random() * canvas_width,
            y: Math.random() * canvas_height,
            radius: 1.5,
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

    const pointsLayer = new Konva.Layer();
    allLabels = [];
    for (let i = 0; i < waypoints.length; i++) {
        const [symbol, label] = getMapSymbol(waypoints[i])
        allLabels.push(label)
        pointsLayer.add(symbol)
    }

    // Prevent overlap of labels
    allLabels = simulatedAnnealing(allLabels, 2)
    for (let i = 0; i < allLabels.length; i++) {
        const label = new Konva.Label({
            x: allLabels[i].x,
            y: allLabels[i].y
        })
        label.add(new Konva.Tag({fill: 'black', opacity: 0.25}));

        // Strip out the system from the waypoint to keep
        // point labels small
        const regex = /[^-]+$/;
        label.add(new Konva.Text({
            text: allLabels[i].value.match(regex),
            fontSize: 12,
            padding: 5,
            fill: 'green'
        }))
        label.on('click', () => {
            showDrawer(allLabels[i].value)
        })
        pointsLayer.add(label)
    }
    stage.add(pointsLayer)
}

function getMapSymbol(point) {
    // convert coordinates to canvas
    // canvas coordinates go 0-1600
    // sector coordinates go from -800 to 800 (assumption)
    // This makes life easier, we just need to add 800
    const padding = 50;
    const map_x = (point.x + 800) + padding;
    const map_y = (1600 - (point.y + 800)) + padding;
    var label = new WaypointLabel(
        map_x + 10,
        map_y - 3,
        point.symbol.waypoint
    )

    switch (point.type) {
        case 'PLANET':
            var imageObj = new Image();
            imageObj.src = '/assets/world.svg'
            var symbol = new Konva.Image({
                height: 30,
                width: 40,
                image: imageObj,
            });
            break;
        case 'ENGINEERED_ASTEROID':
            var imageObj = new Image();
            imageObj.src = '/assets/asteroid-red.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;

        case 'JUMP_GATE':
            var imageObj = new Image();
            imageObj.src = '/assets/warp-gate.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;
        case 'FUEL_STATION':
            var imageObj = new Image();
            imageObj.src = '/assets/apollo-capsule-blue.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;

        case 'ORBITAL_STATION':
            var imageObj = new Image();
            imageObj.src = '/assets/orbital-station.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;
        case 'ASTEROID':
            var imageObj = new Image();
            imageObj.src = '/assets/asteroid-brown.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;
        case 'ASTEROID_BASE':
            var imageObj = new Image();
            imageObj.src = '/assets/asteroid-blue.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;
        case 'GAS_GIANT':
            var imageObj = new Image();
            imageObj.src = '/assets/gas-giant.svg'
            var symbol = new Konva.Image({
                height: 35,
                width: 35,
                image: imageObj,
            });
            break;

        case 'MOON':
            var imageObj = new Image();
            imageObj.src = '/assets/moon.svg'
            var symbol = new Konva.Image({
                height: 20,
                width: 20,
                image: imageObj,
            });
            break;
        default:
            console.log(point.type)
            var imageObj = new Image();
            imageObj.src = '/assets/uncertainty.svg'
            var symbol = new Konva.Image({
                height: 25,
                width: 25,
                image: imageObj,
            });
            break;
    }

    symbol.on('click', () => {
        showDrawer(point.symbol.waypoint)
    })


    var group = new Konva.Group({
        x: map_x,
        y: map_y,
    })

    group.add(symbol);
    return [group, label];
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
            drawMap(data);
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
