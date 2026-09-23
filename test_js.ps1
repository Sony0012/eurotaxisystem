$html = New-Object -ComObject "htmlfile"
$html.IHTMLDocument2_write("<html><body><script>window.onerror = function(e){ document.write('ERROR:'+e); }; try{ eval('function () { return }'); }catch(e){ document.write('EVAL:'+e.message); }</script></body></html>")
Write-Output $html.body.innerHTML
