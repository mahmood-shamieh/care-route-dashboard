<?php
print('<script>
var Cookies = document.cookie.split(";"); 
for (var i = 0; i < Cookies.length; i++)
document.cookie = Cookies[i] + "=;expires=" + new Date(0).toUTCString();
</script>');
