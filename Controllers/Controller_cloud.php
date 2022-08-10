<?php

class Controller_cloud extends Controller{

    public function action_default(){
        echo "<script>alert(\"coucou\")</script>";
        $this->render('cloud');
    }


    public function action_PageInfo()
    {
        $m = Model::getModel();
        $infoFile = $m->getDocByID($_GET["FileId"]);//get les infos du doc via son ID

        $pathFile = "src/Upload/".$infoFile["Filename"];
        $extension = pathinfo($pathFile, PATHINFO_EXTENSION);

        if ($infoFile["Type"] != "pdf" and $infoFile["Type"] != "txt"){//si c'est un fichier audio/vidéo
            $TranscriptFile = "src/MediaToText/".$infoFile["TranscriptFile"];////get son fichier de transcription
        }else{
            $TranscriptFile = "None";
        }

        $arrayKeyWord = $m->getMot($_GET["FileId"]);//Recupere la liste de mots clés du document courant

        $DocSimi = $m->CloudDocumentSimilaire($arrayKeyWord);//recupere la liste des documents similaires

        $this->render('cloud',['Name'=>$infoFile["Name"],'tabWord'=>$arrayKeyWord,'PathFile'=>$pathFile,'Description'=>$infoFile["Description"],'Tags'=>$infoFile["Tags"],'TranscriptFile'=>$TranscriptFile,'Extension'=>$extension, "ListeDocuSim"=>$DocSimi]);
    }
}

?>
