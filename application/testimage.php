<div id="input-upload-file" class="box-shadow">
   <span>upload! (ღ˘⌣˘ღ)</span> <!--ignore the text face lol -->
   <input type="file" class="upload" id="fileUp" name="fileUpload" onchange="setFileInfo()">
</div>
<div id="infos">
    
</div>

<script>
    var myVideos = [];

window.URL = window.URL || window.webkitURL;

document.getElementById('fileUp').onchange = setFileInfo;

function setFileInfo() {
  var files = this.files;
  myVideos.push(files[0]);
  var video = document.createElement('video');
  video.preload = 'metadata';

  video.onloadedmetadata = function() {
    window.URL.revokeObjectURL(video.src);
    var duration = video.duration;
    myVideos[myVideos.length - 1].duration = duration;
    updateInfos();
  }

  video.src = URL.createObjectURL(files[0]);;
}


function updateInfos() {
  var infos = document.getElementById('infos');
  infos.textContent = "";
  for (var i = 0; i < myVideos.length; i++) {
    infos.textContent += myVideos[i].name + " duration: " + toHHMMSS(myVideos[i].duration) + '\n';
  }
}

function toHHMMSS(durationStr) {
    var sec_num = parseInt(durationStr, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes+':'+seconds;
}
</script>