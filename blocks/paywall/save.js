import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function Save(props) {
	const blockProps = useBlockProps.save();

	return (
		<div className={props.className} {...blockProps}>
			<InnerBlocks.Content />
		</div>
	);
}
