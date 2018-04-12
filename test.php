<?php
class word
{
    function start()
    {
        ob_start();
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">';
    }
    function save($path)
    {

        echo "</html>";
        $data = ob_get_contents();
        ob_end_clean();

        $this->wirtefile ($path,$data);
    }

    function wirtefile ($fn,$data)
    {
        $fp=fopen($fn,"wb");
        fwrite($fp,$data);
        fclose($fp);
    }
}

$html = '
<table width=600 cellpadding="6" cellspacing="1" bgcolor="#336699">
<tr bgcolor="White">
 <td>PHP10086</td>
 <td><a href="http://www.php10086.com" target="_blank" ><a rel="nofollow" href="http://www.php10086.com<" target="_blank">http://www.php10086.com<</a>;/a></td>
</tr>
<tr bgcolor="red">
 <td>PHP10086</td>
 <td><a href="http://www.php10086.com" target="_blank" ><a rel="nofollow" href="http://www.php10086.com<" target="_blank">http://www.php10086.com<</a>;/a></td>
</tr>
<tr bgcolor="White">
 <td colspan=2 >
 PHP10086<br>
 最靠谱的PHP技术博客分享网站
 <img src="http://www.php10086.com/wp-content/themes/WPortal-Blue/images/logo.gif">
 </td>
</tr>
</table>
';

file_put_contents('1.word',$html);