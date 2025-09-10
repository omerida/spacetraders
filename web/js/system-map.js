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
        const maxMoveDistance = 50;
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
    const stage = new Konva.Stage({
        container: 'system-map', // id of container <div>
        width: 800,
        height: 800,
        fill: 'black'
    });

    const background = new Konva.Layer();
    background.add(new Konva.Rect({
        x: 0,
        y: 0,
        width: stage.width(),
        height: stage.height(),
        fill: '#222'
    }));
    stage.add(background);

    const grid = new Konva.Layer();
    for (let i = 100; i < stage.width(); i = i + 100) {
        grid.add(new Konva.Line({
            points: [i, 0, i, stage.height()],
            stroke: '#666',
            strokeWidth: 1,
            dash: [6, 6]
        }))

        grid.add(new Konva.Line({
            points: [0, i, stage.width(), i],
            stroke: '#666',
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
    allLabels = simulatedAnnealing(allLabels, 30)
    for (let i = 0; i < allLabels.length; i++) {
        const label = new Konva.Label({
            x: allLabels[i].x,
            y: allLabels[i].y
        })
        label.add(new Konva.Tag({fill: 'black', opacity: 0.55}));

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
    // canvas coordinates go 0-800
    // sector coordinates go from -400 to 400 (assumption)
    // This makes life easier, we just need to add 400
    const map_x = point.x + 400;
    const map_y = 800 - (point.y + 400);
    var label = new WaypointLabel(
        map_x + 5,
        map_y + (Math.random() < 0.5 ? -1 : 1) * 25,
        point.symbol.waypoint
    )

    switch (point.type) {
        case 'PLANET':
            var imageObj = new Image();
            imageObj.src = '/assets/world.svg'
            var symbol = new Konva.Image({
                height: 40,
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
        case 'FUEL_STATION':
            var imageObj = new Image();
            imageObj.src = '/assets/apollo-capsule-blue.svg'
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
        default:
            console.log(point.type)
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
    let layer = up.layer.open({
        url: '/systems/waypoint?id=' + waypoint,
        target: '.content',
        layer: 'swap',
        mode: 'drawer',
        size: 'grow'
    })

}