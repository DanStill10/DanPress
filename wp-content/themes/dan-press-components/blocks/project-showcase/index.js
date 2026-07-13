import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import Edit from './edit';

registerBlockType('dsd-lib/showcase-card', {
  edit: Edit,
  save: ({ attributes }) => {
    const blockProps = useBlockProps.save({ className: 'dsd-showcase' });
    return (
      <div {...blockProps}>
        <div className="dsd-card-top">
          <span className="dsd-badge">{attributes.envTag}</span>
          <span className="dsd-tech-stack">{attributes.techList}</span>
        </div>
        <h3 className="dsd-card-title">{attributes.title}</h3>
        <p className="dsd-card-text">{attributes.tagline}</p>
        <div className="dsd-card-action">
          <a href={attributes.repoLink} className="dsd-link" target="_blank" rel="noreferrer">
            VIEW_PROJECT <span className="dsd-arrow">&rarr;</span>
          </a>
        </div>
      </div>
    );
  }
});
