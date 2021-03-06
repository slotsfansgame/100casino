//<?php
/**
 * phx 
 * 
 * (Placeholders Xtended) Adds the capability of output modifiers when using placeholders, template variables and settings tags
 *
 * @category    plugin
 * @version     2.2.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @author      Armand "bS" Pondman (apondman@zerobarrier.nl)
 * @internal    @properties &phxdebug=Log events;int;0 &phxmaxpass=Max. Passes;int;50
 * @internal    @events OnParseDocument
 * @internal    @modx_category Manager and Admin
 * @internal    @legacy_names phx
 * @internal    @installset base, sample
 */

include_once $modx->config['rb_base_dir'] . "plugins/phx/phx.parser.class.inc.php";

$e = &$modx->Event;

$PHx = new PHxParser($phxdebug,$phxmaxpass);

switch($e->name) {
    case 'OnParseDocument':
        $PHx->OnParseDocument();
        break;

}