<?php

class Controller_cloud extends Controller{

    public function action_default(){
        echo "<script>alert(\"coucou\")</script>";
        $this->render('cloud');
    }


    public function action_PageInfo()
    {
        $m = Model::getModel();
        $infoFile = $m->getDocByID($_GET["FileId"]);

        $pathFile = "src/Upload/".$infoFile["Filename"];
        $extension = pathinfo($pathFile, PATHINFO_EXTENSION);
        if ($infoFile["Type"] != "pdf" and $infoFile["Type"] != "txt"){
            $TranscriptFile = "src/MediaToText/".$infoFile["TranscriptFile"];
        }else{
            $TranscriptFile = "None";
        }

        $arrayKeyWord = $m->getMot($_GET["FileId"]);

        $DocSimi = $m->CloudDocumentSimilaire($arrayKeyWord);

        //$this->render("test",["liste"=>$DocSimi]);

        $this->render('cloud',['Name'=>$infoFile["Name"],'tabWord'=>$arrayKeyWord,'PathFile'=>$pathFile,'Description'=>$infoFile["Description"],'Tags'=>$infoFile["Tags"],'TranscriptFile'=>$TranscriptFile,'Extension'=>$extension, "ListeDocuSim"=>$DocSimi]);
    }
}

?>
