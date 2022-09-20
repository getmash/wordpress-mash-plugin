import { useEffect } from 'react';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import useScript from '../utils/useScript';

const ICON_OPTIONS = [
	{ label: 'lightning', value: 'lightning' },
	{ label: 'heart', value: 'heart' },
	{ label: 'fire', value: 'fire' },
];

const LAYOUT_OPTIONS = [
	{ label: 'float', value: 'float' },
	{ label: 'inline', value: 'inline' },
];

const FLOAT_LOCATION_OPTIONS = [
	{ label: 'top-left', value: 'top-left' },
	{ label: 'top-center', value: 'top-center' },
	{ label: 'top-right', value: 'top-right' },
	{ label: 'bottom-left', value: 'bottom-left' },
	{ label: 'bottom-center', value: 'bottom-center' },
];

const VARIANT_OPTIONS = [
	{ label: 'colorized', value: 'colorized' },
	{ label: 'light', value: 'light' },
	{ label: 'dark', value: 'dark' },
];

export default function Edit(props) {
	// https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#block-wrapper-props
	const blockProps = useBlockProps();

	const { attributes } = props;

	const setAttribute = (key, value) => {
		props.setAttributes({ [key]: value });
	};

	useScript('https://components.getmash.com/boost/boost.js');

	return (
		<div className={props.className} {...blockProps}>
			<InspectorControls key="inspector">
				<PanelBody title="Mash Boost Options">
					<PanelRow>
						<SelectControl
							label="Variant"
							value={attributes.variant}
							options={VARIANT_OPTIONS}
							onChange={(value) => setAttribute('variant', value)}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Icon"
							value={attributes.icon}
							options={ICON_OPTIONS}
							onChange={(value) => setAttribute('icon', value)}
						/>
					</PanelRow>
					<PanelRow>
						<SelectControl
							label="Layout"
							value={attributes['layout-mode']}
							options={LAYOUT_OPTIONS}
							onChange={(value) =>
								setAttribute('layout-mode', value)
							}
						/>
					</PanelRow>
					{attributes['layout-mode'] === 'float' && (
						<PanelRow>
							<SelectControl
								label="Location"
								value={attributes['float-location']}
								options={FLOAT_LOCATION_OPTIONS}
								onChange={(value) =>
									setAttribute('float-location', value)
								}
							/>
						</PanelRow>
					)}
				</PanelBody>
			</InspectorControls>

			<ServerSideRender
				block="mash/boost-button"
				attributes={attributes}
			/>

			{attributes['layout-mode'] === 'float' && (
				<div
					style={{
						backgroundColor: 'rgba(0,0,0,0.1)',
						fontSize: '1rem',
						padding: '1rem 0.75rem',
					}}
				>
					Mash boost button block placeholder. Button is in float
					mode. Click to edit block.
				</div>
			)}
		</div>
	);
}
