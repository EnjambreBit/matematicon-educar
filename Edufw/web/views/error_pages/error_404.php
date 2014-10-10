<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Página no encontrada</title>
        <style>
            /*
            Document   : error.css
            Created on : 11/07/2012, 16:33:33
            Author     : sruizdiaz
            Description: estilos vista de error
            */

            /* http://meyerweb.com/eric/tools/css/reset/
               v2.0 | 20110126
               License: none (public domain)
            */

            html, body, div, span, applet, object, iframe,
            h1, h2, h3, h4, h5, h6, p, blockquote, pre,
            a, abbr, acronym, address, big, cite, code,
            del, dfn, em, img, ins, kbd, q, s, samp,
            small, strike, strong, sub, sup, tt, var,
            b, u, i, center,
            dl, dt, dd, ol, ul, li,
            fieldset, form, label, legend,
            table, caption, tbody, tfoot, thead, tr, th, td,
            article, aside, canvas, details, embed,
            figure, figcaption, footer, header, hgroup,
            menu, nav, output, ruby, section, summary,
            time, mark, audio, video {
                margin: 0;
                padding: 0;
                border: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
            }
            /* HTML5 display-role reset for older browsers */
            article, aside, details, figcaption, figure,
            footer, header, hgroup, menu, nav, section {
                display: block;
            }
            body {
                line-height: 1;
            }
            ol, ul {
                list-style: none;
            }
            blockquote, q {
                quotes: none;
            }
            blockquote:before, blockquote:after,
            q:before, q:after {
                content: '';
                content: none;
            }
            table {
                border-collapse: collapse;
                border-spacing: 0;
            }
            .clear{clear:both;}
            .audible{
                position: absolute;
                text-indent: -9999px;
                top:auto;
                width:1px;
                height:1px;
                overflow:hidden;
            }

            h1, h2, h3, h4, h5, h6 {
                font-weight: normal;
            }

            /*** ESTILOS PROPIOS ***/

            body {
                font: bold 16px/1em Arial, Helvetica, sans-serif;
                padding-top: 112px;
            }
            body.educar {
                color: #999999;
            }
            #contenedor{
                width: 600px;
                margin: 0 auto;
                height: 100%;
                overflow: hidden;
            }
            .msj_error, .msj_mantenimiento {
                clear: both;
                padding: 22px 270px 63px 0;
            }
            h1 {
                border-bottom: 1px solid #E4E4E4;
                box-shadow: 0 2px 0 rgba(255, 255, 255, 0.2);
                font-size: 1em;
                font-weight: bold;
                margin-bottom: 25px;
                padding-bottom: 12px;
                text-align: right;
            }
            .educar h1 {color: #63a9da;}
            p {
                font-size: 0.875em;
                line-height: 1.25em;
                text-align: right;
            }
        </style>
    </head>
    <body class="educar">
        <!-- BODYCLASS DEBE SER UN SERVICIO PARA ESTA -->
        <div id="contenedor">
            <div class="msj_error">
                <h1>Error 404</h1>
                <p>Página no encontrada</p>
            </div>
        </div>
    </body>
</html>
