<?php
namespace Edufw\utils;

/**
 * Description of treeBuilder
 *
 * @author lucio
 */
class TreeBuilder {

  /**
   * Funcion recursiva que va creando el arbol a partir de una raiz
   * @param Array $data Array de datos obtenidos de una consulta sql
   * @param String $root raiz a partir de la cual se quiere creear el arbol
   * @return Array SubArbol del arbol principal 
   */
  public static function addNode($data, $root) {
    $tree = array();
    foreach ($data as $one) {
      if ($one["id_padre"] == $root) {
        $leaf = $one;
        $leaf["hijos"] = TreeBuilder::addNode($data, $one["id"]);
        array_push($tree, $leaf);
      }
    }
    if (!empty($tree))
      return $tree;

    return null;
  }

  /**
   * Funcion recursiva que clona una porcion del arbol a partir de una raiz
   * @param Array $data Arbol de datos a clonar
   * @param String $root raiz a partir de la cual se quiere creear el arbol
   * @return Array SubArbol del arbol principal 
   */
  public static function cloneNode($tree, $root) {
    $subarbol = null;
    $total = count($tree);
    $i = 0;
    while($subarbol == null && $i < $total)    
    {
      if ($tree[$i]["id"] == $root)
        return $tree[$i];
      if (!empty($tree[$i]['hijos']))
        $subarbol = TreeBuilder::cloneNode($tree[$i]['hijos'], $root);
      $i++;
    }
    return $subarbol;
  }

  /**
   * Funcion recursiva que va creando el arbol yui a partir de una raiz
   * 
   * (ARRAYS DEBEN CONTENER): id (ID del nodo), desc (Label del nodo), id_padre (A quién pertenece el nodo, null si no tiene padre)
   * 
   * (ARRAYS PUEDEN CONTENER): labelStyle (Utilizado como nombre de clase para poder darle diseño), editable (Si el nodo es editable), expanded (Si el nodo es expandible)
   * 
   * @param String $label String con el nombre que contendrá el nodo
   * @param String $item_type String que indica que tipo de nodo es
   * @param String $label_style String que indica el estilo del nodo
   * @param integer $node_id id del nodo actual (null, para generar todo el tree)
   * @param array $nodes Array que posee toda la estructura del tree
   * @param array $elements Array que posee toda la estructura del tree
   * @param boolean [$editable] Default true, indica si el nodo es editable o no
   * @param boolean [$expanded] Default true, indica si el nodo es expandible o no
   * 
   * @return YuiTreeArray
   */
  public static function addYuiTreeNode($label, $item_type, $label_style, $node_id, $nodes, $elements, $editable = true, $expanded = true) {
    $tree = array();

    foreach ($nodes as $node) {
      if ($node_id == $node['id']) {
        foreach ($node as $key => $value) {
          $tree[$key] = $value;
        }
      }
    }

    $tree['label'] = $label;
    $tree['labelStyle'] = $label_style;
    $tree['item_type'] = $item_type;
    $tree['editable'] = $editable;
    $tree['expanded'] = $expanded;

    foreach ($nodes as $node) {
      if ($node_id == $node['id_padre']) {
        $label_style = (isset($node['labelStyle'])) ? $node['labelStyle'] : 'nodo';
        $item_type = (isset($node['item_type'])) ? $node['item_type'] : 'nodo';
        $editable = (isset($node['editable'])) ? $node['editable'] : true;
        $expanded = (isset($node['expanded'])) ? $node['expanded'] : true;
        $tree['children'][] = self::addYuiTreeNode($node['desc'], $item_type, $label_style, $node['id'], $nodes, $elements, $editable, $expanded);
      }
    }
    $tree[] = self::addYuiTreeElement($tree, $node_id, $elements);
    return $tree;
  }

  private static function addYuiTreeElement(&$tree, $node_id, $elements) {
    foreach ($elements as $element) {
      if ($node_id == $element['id_padre']) {
        foreach ($element as $key => $value) {
          $elemento[$key] = $value;
        }
        $elemento['type'] = 'text';
        $elemento['label'] = $element['desc'];
        $elemento['labelStyle'] = (isset($element['labelStyle'])) ? $element['labelStyle'] : 'elemento';
        $elemento['item_type'] = (isset($element['item_type'])) ? $element['item_type'] : 'elemento';
        $tree['children'][] = $elemento;
      }
    }
    return $tree;
  }

}