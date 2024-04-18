var PageLoaded = false;

window.onload = function() {
   PageLoaded = true;

   if (PageLoaded == true) {
        site_load();
        $(window).resize(function () {
            site_load();
        })
   }
}


function site_load() {
    // setTimeout(() => {
    //     document.documentElement.classList.add("loaded")
    // }, 0);
    
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)) {
        document.documentElement.classList.add("touch")
    } else document.documentElement.classList.remove('touch')
}