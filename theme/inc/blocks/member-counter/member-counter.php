
<?php
/**
 * Gold Rush Member Counter Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

 $form_id = 1;
 $search_criteria = array();
 $sorting         = array();
 $paging          = array();
 $total_count     = 0;
 $results         = GFAPI::get_entry_ids( $form_id, $search_criteria, $sorting, $paging, $total_count );



$anchor = '';
if ( !empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'counters goldrush-member-counter  text-center font-mono text-6xl not-prose counters';

?>
<div id="member-counter" class="<?php echo $class_name; ?>">
	<p><span class="counterOne text-6xl font-mono" data-number="<?php echo sizeof($results); ?>">0</span></p>

</div>
