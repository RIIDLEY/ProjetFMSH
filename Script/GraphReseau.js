//init le graphique sigma
var s = new sigma(
    {
        renderer: {
            container: document.getElementById('sigma-container'),
            type: 'canvas'
        },
        settings: {
            scalingMode: 'outside',
            drawLabels: false,
            maxNodeSize: 10,
            minNodeSize: 2,
        }
    }
);
var nbNode = 50;
var nbEdge = 100;

var graph = {
    nodes: [],
    edges: []
}

for (i = 0; i < nbNode; i++)
    graph.nodes.push({
        id:  i,
        label: 'Node ' + i,
        x: Math.random(),
        y: Math.random(),
        size: 1,
        color: '#EE651D'
    });

for (i = 0; i < nbEdge; i++)
    graph.edges.push({
        id: i,
        label: 'Edge ' +i,
        source: '' + (Math.random() * nbNode | 0),
        target: '' + (Math.random() * nbNode | 0),
        color: '#00000',
        type: 'curvedArrow',
    });

// load the graph
s.graph.read(graph);
// draw the graph
s.refresh();
// launch force-atlas for 5sec
s.startForceAtlas2();
window.setTimeout(function() {s.killForceAtlas2()}, 10000);