<?php
require('view_begin.php');
include 'Utils/import_sigmaJS.php';

?>
    <script>
        var element = document.getElementById("home");//Modifie la navbar en fonction de la page actuel
        element.classList.add("active");

    </script>

    <div class="container">
        <div class="row">
            <div class="col DivTools">
                <form class="form-inline" action = "?controller=home&action=recherche" method="post" style="display: inline-block;">
                    <div class="input-group mb-3">
                    <input type="text" class="form-control" name="KeyWords" size="50" placeholder="Mot clés"/>
                    </div>
                    <div class="input-group-append">
                        <input type="submit" value="Chercher" class="btn btn-primary"/></form>
                    </div>

            </div>
        </div>
    <div class="row">
        <div class="divStyle col-md-auto">
        <?php
        if (isset($ListFiles)){

        foreach ($ListFiles as $key => $value){ ?>

            <a href="?controller=cloud&action=PageInfo&FileId=<?=$value["FileID"]?>" target="_blank" style="text-decoration:none;color: inherit;"><li class="list-group-item">Document : <strong><?=$value["Name"]?></strong></li></a>
        <?php
        }?>
            <script>
                var my_javascript_variable = <?php echo json_encode($ListFiles) ?>;
            </script>
        <?php } ?>
    </div>
        <div class="col">
            <div id='sigma-container'></div>
        </div>

    </div>
        <div class="row">
            <di class="col DivTools">
                <h4><u>Outils :</u></h4>
                <button id="degreeEntrant" class="btn btn-primary" style="margin: 10px">Degrés entrant</button>
                <button id="degreeSortant" class="btn btn-primary" style="margin: 10px">Degrés sortant</button>
                <button id="textEtat" class="btn btn-primary" style="margin: 10px">Afficher les labels</button>
                <button id="resetdegree" class="btn btn-primary" style="margin: 10px">Reinitialiser</button>
            </di>

            <di class="col DivDataInfo" id="DivInfo" style="display: none;">
                <h4><u>Informations :</u></h4>
                <ul>
                    <span><strong>Titre : </strong><span id="TitleInfo"></span></span>
                    <span id="DescInfoLI" style="display: none;"><strong>Description : </strong><span id="DescInfo"></span></span>
                    <span id="InDegreeInfoLI" style="display: none;"><strong>Degrée entrant : </strong><span id="InDegreeInfo"></span></span>
                    <span id="OutDegreeInfoLI" style="display: none;"><strong>Degrée sortant : </strong><span id="OutDegreeInfo"></span></span>
                    <span style="display: none;" id="LinkInfoP" >Plus d'information <a href="#" id="LinkInfoA" target="_blank">ici</a></span>
                </ul>

            </di>
        </div>
    </div>

<?php
if (isset($ListeKeyWords)){?>
    <script type="text/javascript" >

        sigma.classes.graph.addMethod('neighbors', function(nodeId) {//ajoute la méthode pour trouver les voisins
            var k,
                neighbors = {},
                index = this.allNeighborsIndex[nodeId] || {};

            for (k in index)
                neighbors[k] = this.nodesIndex[k];

            return neighbors;
        });


        function uniq(a) {//fonction permettant de supprimer les doublons
            var seen = {};
            return a.filter(function (item) {
                return seen.hasOwnProperty(item) ? false : (seen[item] = true);
            });
        }

 /*-----------------------------------------------------------------------*/
        var ArrayInfos = <?php echo json_encode($ListeKeyWords); ?>;//get le tableau d'information
        var ArrayInfosDesc = <?php echo json_encode($ListFiles); ?>;
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

        var graph = {
            nodes: [],
            edges: []
        }

        var KeyWordsArraySigma = [];

        for (i = 0; i < ArrayInfos.length; i++){//node files
            graph.nodes.push({
                id:  i,
                label: ArrayInfos[i]["FileName"],
                x: Math.random(),
                y: Math.random(),
                size: 4,
                originSize : 4,
                color: '#0080ff',
                originalColor: '#0080ff',
                fileID : ArrayInfos[i]["FileID"],
                Description : ArrayInfosDesc[i]["Description"],
                outDegree : 0,
                inDegree : 0
            });
            var Arraytmp = ArrayInfos[i]["ListKeyWords"];//get les tags de la ligne courante
            KeyWordsArraySigma.push.apply(KeyWordsArraySigma, Arraytmp);//push dans le tableau
        }

        KeyWordsArraySigma = uniq(KeyWordsArraySigma);

        for (i = 0; i < KeyWordsArraySigma.length; i++){//node keywords
            graph.nodes.push({
                id:  i + ArrayInfos.length,
                label: KeyWordsArraySigma[i][0],
                x: Math.random(),
                y: Math.random(),
                size: 2,
                originSize : 2,
                color: '#ff0000',
                originalColor: '#ff0000',
                Description : "Mot-clé",
                outDegree : 0,
                inDegree: 0
            });

        }

        for (let i = 0; i < ArrayInfos.length; i++) {//réalisation des liaisons entre les documents et les mots-clés
            var Arraytmp = ArrayInfos[i]["ListKeyWords"];//get les tags de la ligne courante

            for (let j = 0; j < Arraytmp.length; j++) {//parcours les tags
                var tmp = Object.keys(KeyWordsArraySigma).find(key => KeyWordsArraySigma[key] == KeyWordsArraySigma[j]);//get l'id node du tags

                graph.edges.push({//fait la liaison
                    id: graph.edges.length + 1,
                    source: i,
                    target: parseInt(tmp) + parseInt(ArrayInfos.length),
                    color: '#000',
                    type: 'curvedArrow',
                    data: {
                        properties: {
                            aString: 'abc ' + i,
                            aBoolean: false,
                            anInteger: i,
                            aFloat: Math.random(),
                            anArray: [1, 2, 3]
                        }
                    }
                })
                graph.nodes[parseInt(tmp) + parseInt(ArrayInfos.length)].inDegree +=1;//+1 au nombre de degré entant dans le node tags
                graph.nodes[i].outDegree += 1;//+1 au nombre de degré sortant du node titre
            }
        }

        // load the graph
        s.graph.read(graph);
        // draw the graph
        s.refresh();
        // launch force-atlas for 5sec
        s.startForceAtlas2();
        window.setTimeout(function() {s.killForceAtlas2()}, 10000);

        s.bind('clickNode', function(e) {//si un node est selectionné

            document.getElementById("DivInfo").style.display = "block";
            document.getElementById("TitleInfo").textContent = e.data.node.label;

            if (e.data.node.Description!="Mot-clé"){

                document.getElementById("OutDegreeInfoLI").style.display = "block";
                document.getElementById("OutDegreeInfo").textContent = e.data.node.outDegree;

                document.getElementById("DescInfoLI").style.display = "block";
                document.getElementById("DescInfo").textContent = e.data.node.Description;

                document.getElementById("InDegreeInfoLI").style.display = "none";

                document.getElementById("LinkInfoP").style.display = "block";
                document.getElementById("LinkInfoA").href = "?controller=cloud&action=PageInfo&FileId="+e.data.node.fileID;
            }else{

                document.getElementById("DescInfoLI").style.display = "none";

                document.getElementById("OutDegreeInfoLI").style.display = "none";

                document.getElementById("LinkInfoP").style.display = "none";

                document.getElementById("InDegreeInfoLI").style.display = "block";
                document.getElementById("InDegreeInfo").textContent = e.data.node.inDegree;
            }

            var nodeId = e.data.node.id,
                toKeep = s.graph.neighbors(nodeId);
            toKeep[nodeId] = e.data.node;
            s.graph.nodes().forEach(function(n) {
                if (toKeep[n.id])
                    n.color = '#24ff03';
                else
                    n.color = n.originalColor;
            });

            s.graph.edges().forEach(function(e) {
                if (toKeep[e.source] || toKeep[e.target]){
                    e.color = '#24ff03';
                }
                else
                    e.color = e.originalColor;
            });

            //Refresh graph to update colors
            s.refresh();
        });

        document.addEventListener('keydown', function(event){
            if(event.key === "Escape"){// si touche echap est pressé, retire les couleurs de voisinage
                document.getElementById("DivInfo").style.display = "none";
                s.graph.nodes().forEach(function(n) {
                    n.color = n.originalColor,
                        n.hidden = false;
                });

                s.graph.edges().forEach(function(e) {
                    e.color = '#000',
                        e.hidden = false;
                });

                //Refresh graph to update colors
                s.refresh();
            }
        });

        const degreeEntrant = document.getElementById('degreeEntrant').addEventListener("click", () => {
            s.graph.nodes().forEach(function(n) {
                n.size = n.originSize;
            });
            s.refresh();

            s.graph.nodes().forEach(function(n) {
                if (n.inDegree != null){
                    n.size = n.inDegree;
                }
            });
            s.refresh();
        })

        const degreeSortant = document.getElementById('degreeSortant').addEventListener("click", () => {
            s.graph.nodes().forEach(function(n) {
                n.size = n.originSize;
            });
            s.refresh();

            s.graph.nodes().forEach(function(n) {
                if (n.outDegree != null){
                    n.size = n.outDegree;
                }
            });
            s.refresh();
        })

        const resetdegree = document.getElementById('resetdegree').addEventListener("click", () => {
            s.graph.nodes().forEach(function(n) {
                n.size = n.originSize;
            });
            s.refresh();
        })

        const textEtat = document.getElementById('textEtat').addEventListener("click", () => {
            if(s.settings('drawLabels')){
                s.settings('drawLabels', false);
            }else {
                s.settings('drawLabels', true);
            }
            s.refresh();


        })

    </script>

<?php
}
require('view_end.php');
?>