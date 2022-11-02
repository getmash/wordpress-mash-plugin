import { useEffect, useState } from 'react';
import {
	useBlockProps,
	InspectorControls,
	InnerBlocks,
	Warning,
} from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	TextControl,
	TextareaControl,
	ToggleControl,
	Button,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import useScript from '../utils/useScript';

const PAY_PER_USE_URL = 'https://wallet.getmash.com/earn/pay-per-use';
// Based on the regex here: https://ihateregex.io/expr/uuid/
const RESOURCE_ID_REGEX =
	/^[0-9a-f]{8}\b-[0-9a-f]{4}\b-[0-9a-f]{4}\b-[0-9a-f]{4}\b-[0-9a-f]{12}$/i;

export default function Edit(props) {
	// https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#block-wrapper-props
	const blockProps = useBlockProps();
	const [previewPaywall, setPreviewPaywall] = useState(false);
	const [invalidResourceID, setInvalidResourceID] = useState(false);

	const { attributes } = props;
	const setAttribute = (key, value) => props.setAttributes({ [key]: value });

	// Define function for updating validity of resource ID (confirm it's a UUID)
	const updateResourceIDValidity = (resourceID) => {
		const validResourceID =
			resourceID && resourceID.match(RESOURCE_ID_REGEX);
		setInvalidResourceID(!validResourceID);
	};

	// Set the validity of the resource ID when the component first mounts
	useEffect(() => {
		updateResourceIDValidity(attributes['resource']);
	}, []);

	// Pull in the web component's script
	useScript('https://components.getmash.com/content/paywall.js');

	return (
		<div className={props.className} {...blockProps}>
			<InspectorControls key="inspector">
				<PanelBody title="Editor Options">
					<PanelRow>
						<ToggleControl
							label="Preview paywall"
							checked={previewPaywall}
							onChange={(value) => setPreviewPaywall(value)}
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody title="Mash Paywall Options">
					<PanelRow>
						<TextControl
							label="Mash Price Category Tag"
							help="The pricing category identifier provided to you within the Pay-Per-Use section of the Mash web app. (Required.)"
							value={attributes.resource}
							isSelected={focus}
							onChange={(value) => {
								updateResourceIDValidity(value);
								setAttribute('resource', value);
							}}
						/>
					</PanelRow>
					<PanelRow>
						<TextareaControl
							label="Title"
							help="The title to display when the paywall is visible."
							value={attributes.title}
							onChange={(value) => setAttribute('title', value)}
						/>
					</PanelRow>
					<PanelRow>
						<TextareaControl
							label="Subtitle"
							help="The subtitle to display when the paywall is visible."
							value={attributes.subtitle}
							onChange={(value) =>
								setAttribute('subtitle', value)
							}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div>
				{invalidResourceID && (
					// Show a warning if the resource ID has been updated but it's in the wrong format
					<Warning>
						<b>Warning</b>: This block's{' '}
						<i>Mash Price Category Tag</i> may not be set yet, or
						may be in the incorrect format. Please update the
						corresponding field in this block's{' '}
						<i>Mash Paywall Options</i> to match the desired price
						category tag provided within the{' '}
						<a href={PAY_PER_USE_URL}>
							"Pay-Per-Use" section of the Mash web app
						</a>
						.
					</Warning>
				)}
				{previewPaywall ? (
					<ServerSideRender
						block="mash/paywall"
						attributes={attributes}
					/>
				) : (
					<InnerBlocks />
				)}
			</div>
		</div>
	);
}
