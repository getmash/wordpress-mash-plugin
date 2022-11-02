import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import Edit from './edit';
import Save from './save';

/**
 * Registers the block. https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/#registerblocktype
 * Relies on block.json metadata to populate all the settings for the block. See https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/
 * to learn about metadata around blocks.
 */
registerBlockType(metadata.name, {
	edit: Edit,
	save: Save,
});
