<?php

class Controller_cloud extends Controller{

    public function action_default(){
        echo "<script>alert(\"coucou\")</script>";
        $this->render('cloud');
    }

}

?>
