<script type="text/javascript">
var disqus_identifier = window.location.url;
var ds_loaded = false;

function loadDisqus()
{
    var disqus_div = $("#disqus_thread");
    var top = disqus_div.offset().top;
    var disqus_data = disqus_div.data();
    if ( !ds_loaded && $(window).scrollTop() + $(window).height() > top ) 
    {
        ds_loaded = true;
        for (var key in disqus_data) 
        {
            if (key.substr(0,6) == 'disqus') 
            {
                window['disqus_' + key.replace('disqus','').toLowerCase()] = disqus_data[key];
            }
        }
        var dsq = document.createElement('script');
        dsq.type = 'text/javascript';
        dsq.async = true;
        dsq.src = 'https://100casino-online.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    }  
}

$(function () 
{
    var disqus_div = $("#disqus_thread");
    if (disqus_div.size() > 0) 
    {
        $(window).scroll(loadDisqus);      
    }  
}
);
</script>