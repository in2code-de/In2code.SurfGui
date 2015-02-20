<html>
    <head>
        <style>
        pre {float: left; 
             border: 1px solid gray; 
             padding: 1em; 
             margin:0; 
             background-color: #C3FDB8;
             margin-right: 3em;}
        hr {clear: both; margin-bottom: 2em; margin-top: 2em;}
        </style>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
    </head>
<body>

<?php

ini_set('display_errors', 'on');
error_reporting(E_ERROR);
require "rst.php";


echo "
<h1>PHP RestructuredText</h1>

<p>
Esta es una demostración de cómo utilizar
el conversor de texto <em>RestructuredText</em> a HTML implementado
en PHP.


<p>
Para utilizar el conversor tiene que usar una sentencia PHP como
la siguiente:

<pre>
echo RST(\$source);
</pre>

<div style='padding-top: 0.5em; clear: both'></div>

<p>
donde <code>\$source</code>, es el texto en formato <em>RestructuredText</em>.

<h2>Ejemplos</h2>

<p>
A continuación se muestran algunos ejemplos


<hr/>

";


/* Ahora se toman varios textos de ejemplo de
 * una colección y se imprimen en crudo y convertidos
 * a HTML para observar la conversión.
 */


$text_collection = array(
"Hola, este es un texto simple.

En dos partes.
",
"
Un párrafo normal:

::

    Otro en fuente monospace.

"
,

"
=================
Titulo de nivel 1
=================

Titulo nivel 2
==============

Titulo nivel 3
--------------
",
"codigo preformateado::
    
    #include <stdio.h>
    int main(void)
    {
        [..]
        return 1;
    }
",
"Texto con atributos: *italic* or **bold**.",
"- uno
- dos
- tres",
"Mostrar una imagen:

.. image:: ceferino_cara.png
"
);


foreach ($text_collection as $text)
{
    echo "<pre>$text</pre> \n";
    echo RST($text);
    echo "<hr/>";
}

$original_text = "
Bienvenido
----------
";

echo "<PRE>$original_text</PRE>";
$text = RST($original_text);
echo $text;
?>

<hr/>

</body></html>
