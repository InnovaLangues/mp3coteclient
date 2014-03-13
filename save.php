<?php
// Muaz Khan     - www.MuazKhan.com 
// MIT License   - https://www.webrtc-experiment.com/licence/
// Documentation - https://github.com/muaz-khan/WebRTC-Experiment/tree/master/RecordRTC
foreach(array('audio') as $type) {
    if (isset($_FILES["${type}-blob"])) {
        
        $fileName = $_POST["${type}-filename"];
        $uploadDirectory = "upload/".$fileName;
        
        if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
            echo(" problem moving uploaded file");
        }
                
                echo($uploadDirectory);
    }
}
?>