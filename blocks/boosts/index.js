import { registerBlockType } from '@wordpress/blocks';

import Edit from './edit';
import metadata from './block.json';

registerBlockType(metadata.name, {
	edit: Edit,
	save: () => {
		// Letting PHP handle this through the registered shortcode
		return null;
	},
});
