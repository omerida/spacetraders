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
    for (let i=100; i < stage.width(); i = i + 100) {
        grid.add(new Konva.Line({
            points: [i, 0, i, stage.height()],
            stroke: '#666',
            strokeWidth: 1,
            dash: [6,6]
        }))

        grid.add(new Konva.Line({
            points: [0, i, stage.width(), i],
            stroke: '#666',
            strokeWidth: 1,
            dash: [6,6]
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

    for (let i=0; i < waypoints.length; i++) {
        const symbol = getMapSymbol(waypoints[i])
        pointsLayer.add(symbol)
    }

    stage.add(pointsLayer)
}

function getMapSymbol(point) {
    // convert coordinates to canvas
    // canvas coordinates go 0-800
    // sector coordinates go from -400 to 400 (assumption)
    // This makes life easier, we just need to add 400
    console.log(point)
    const map_x = point.x + 400;
    const map_y = 800 - (point.y + 400);
    var label = new Konva.Label({
        x: -50,
        y: 20,
    })

    switch (point.type) {
        case 'PLANET':
            var imageObj = new Image();
            imageObj.src = '/assets/world.svg'
            var symbol = new Konva.Image({
                height: 40,
                width: 40,
                image: imageObj,
            });
            var label = new Konva.Label({
                x: -50,
                y: -50,
            })

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
    }

    symbol.on('click', () => {
        console.log('click ' + point.symbol.waypoint)
    })


    var group = new Konva.Group({
        x: map_x,
        y: map_y,
    })

    label.add(new Konva.Tag({fill: 'black'}));
    label.add(new Konva.Text({
        text: point.symbol.waypoint,
        fontSize:14,
        padding: 5,
        fill:'white'
    }))
    group.add(symbol);
    group.add(label);
    return group;
}

function getMapData() {
    const urlParams = new URLSearchParams(window.location.search);
    const system = urlParams.get('system');

    if (!system) {
        return;
    }

    fetch('/systems/waypoint/map/json?system=' + system)
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

function initPage() {
    getMapData();
}

document.addEventListener('DOMContentLoaded', initPage);