import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({ className: 'dsd-showcase' });

  return (
    <>
      <InspectorControls>
        <PanelBody title="Deployment Settings" initialOpen={true}>
          <SelectControl
            label="Environment Status"
            value={attributes.envTag}
            options={[
              { label: 'Production', value: 'PRODUCTION' },
              { label: 'Staging', value: 'STAGING' },
              { label: 'Archived', value: 'ARCHIVED' }
            ]}
            onChange={(val) => setAttributes({ envTag: val })}
          />
        </PanelBody>
      </InspectorControls>

      <div {...blockProps}>
        <div className="dsd-admin-banner">Dan Still Develops // Editor Component</div>
        <div className="dsd-inline-inputs">
          <TextControl
            label="Project Title"
            value={attributes.title}
            onChange={(val) => setAttributes({ title: val })}
          />
          <TextControl
            label="Stack (Comma separated)"
            value={attributes.techList}
            onChange={(val) => setAttributes({ techList: val })}
          />
        </div>
        <TextControl
          label="Short Description"
          value={attributes.tagline}
          onChange={(val) => setAttributes({ tagline: val })}
        />
        <TextControl
          label="Repository URL"
          value={attributes.repoLink}
          onChange={(val) => setAttributes({ repoLink: val })}
        />
      </div>
    </>
  );
}
