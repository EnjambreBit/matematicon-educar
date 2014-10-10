<?php 
use Edufw\core\EException;
?>

<div id="ajax_container">
    <style type="text/css">
        /*<![CDATA[*/
        body {font-family:"Verdana";font-weight:normal;color:black;background-color:white;}
        h1 { font-family:"Verdana";font-weight:normal;font-size:18pt;color:red }
        h2 { font-family:"Verdana";font-weight:normal;font-size:14pt;color:maroon }
        h3 {font-family:"Verdana";font-weight:bold;font-size:11pt}
        p {font-family:"Verdana";font-size:9pt;}
        pre {font-family:"Lucida Console";font-size:10pt;}
        .version {color: gray;font-size:8pt;border-top:1px solid #aaaaaa;}
        .message {color: maroon;}
        .source {font-family:"Lucida Console";font-weight:normal;background-color:khaki;}
        .callstack {font-family:"Lucida Console";font-weight:normal;background-color:#E0FFFF;}
        .error {background-color: #F08080;}
        /*]]>*/
    </style>
    <div>
    <?php if (isset($exception)): ?>

    <h3>Descripción</h3>
    <p class="message">
    <?php echo nl2br(htmlspecialchars($exception->getMessage(),ENT_QUOTES,'UTF-8')); ?>
    </p>

    <h3>Archivo Fuente:</h3>
    <p>
    <?php echo htmlspecialchars($exception->getFile().'('.$exception->getLine().')' ,ENT_QUOTES,'UTF-8'); ?>
    </p>

    <h3>Stack Trace</h3>
    <div class="callstack">
    <pre>
    <?php echo htmlspecialchars($exception->getTraceAsString() ,ENT_QUOTES,'UTF-8'); ?>
    </pre>
    </div><!-- end of callstack -->

    <div class="version">
    <?php echo date('d-m-Y H:i:s', time()); ?>
    </div>
    </div>

    <div class="source">
    <pre>
    <?php
    $source = EException::getSourceLines($exception->getFile(), $exception->getLine());
    $file = $exception->getFile();
    $line2 = $exception->getLine();
    if(empty($source))  {
        echo 'No hay código fuente disponible.';
    } else  {
        foreach($source as $line=>$code)   {
            if($line!==$line2)   {
                echo htmlspecialchars(sprintf("%05d: %s",$line,str_replace("\t",'    ',$code)) ,ENT_QUOTES,'UTF-8');
            } else    {
                echo "<div class=\"error\">";
                echo htmlspecialchars(sprintf("%05d: %s",$line,str_replace("\t",'    ',$code)) ,ENT_QUOTES,'UTF-8');
                echo "</div>";
            } } }
    ?>
    </pre>
    </div><!-- end of source -->

    <?php else: ?>
        <span style="color: red; font-weight: bold;">Error no definido</span>
    <?php endif; ?>
</div>