import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps();
  const { projectTitle } = attributes;

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Project Settings', 'dan-press')}>
          <TextControl
            label={__('Project Title', 'dan-press')}
            value={projectTitle}
            onChange={(val) => setAttributes({ projectTitle: val })}
          />
        </PanelBody>
      </InspectorControls>
      <div {...blockProps}>
        <p>
          <strong>Project Showcase:</strong> {projectTitle || 'Enter title in the sidebar...'}
        </p>
      </div>
    </>
  );
}