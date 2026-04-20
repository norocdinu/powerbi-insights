/**
 * PowerBI Insights — Gutenberg Block Editor Definitions
 *
 * Registers the editor-side UI for the three custom blocks.
 * The server-side rendering (PHP) handles the frontend output.
 */

(function (blocks, element, blockEditor, components, i18n) {
  'use strict';

  const { registerBlockType } = blocks;
  const { createElement: el, Fragment } = element;
  const { InspectorControls, RichText, useBlockProps } = blockEditor;
  const { PanelBody, TextControl, SelectControl, ToggleControl } = components;
  const { __ } = i18n;

  /* ============================================================
     BLOCK 1: Power BI Tip
     ============================================================ */

  registerBlockType('powerbi-insights/powerbi-tip', {
    title:       __('Power BI Tip', 'powerbi-insights'),
    description: __('A highlighted tip, note, or warning box.', 'powerbi-insights'),
    category:    'powerbi-insights',
    icon:        'lightbulb',
    supports:    { html: false },
    attributes: {
      tipTitle: { type: 'string', default: '' },
      tipType:  { type: 'string', default: 'tip' },
      content:  { type: 'string', default: '', source: 'html', selector: '.highlight-box-content p' },
    },

    edit: function (props) {
      const { attributes, setAttributes } = props;
      const { tipTitle, tipType, content } = attributes;

      const typeMap = {
        tip:     { icon: '💡', cls: 'tip-box' },
        note:    { icon: '📝', cls: 'note-box' },
        warning: { icon: '⚠️', cls: 'warning-box' },
        powerbi: { icon: '📊', cls: 'powerbi-tip' },
      };
      const current = typeMap[tipType] || typeMap.tip;

      const blockProps = useBlockProps({ className: 'highlight-box ' + current.cls });

      return el(
        Fragment,
        null,
        el(
          InspectorControls,
          null,
          el(
            PanelBody,
            { title: __('Tip Settings', 'powerbi-insights'), initialOpen: true },
            el(SelectControl, {
              label: __('Type', 'powerbi-insights'),
              value: tipType,
              options: [
                { label: __('Tip 💡', 'powerbi-insights'),        value: 'tip' },
                { label: __('Note 📝', 'powerbi-insights'),        value: 'note' },
                { label: __('Warning ⚠️', 'powerbi-insights'),    value: 'warning' },
                { label: __('Power BI 📊', 'powerbi-insights'),   value: 'powerbi' },
              ],
              onChange: function (val) { setAttributes({ tipType: val }); },
            }),
            el(TextControl, {
              label:    __('Title (optional)', 'powerbi-insights'),
              value:    tipTitle,
              onChange: function (val) { setAttributes({ tipTitle: val }); },
            })
          )
        ),
        el(
          'div',
          blockProps,
          el('div', { className: 'highlight-box-icon', 'aria-hidden': 'true' }, current.icon),
          el(
            'div',
            { className: 'highlight-box-content' },
            tipTitle && el('strong', null, tipTitle),
            el(RichText, {
              tagName:          'p',
              value:            content,
              onChange:         function (val) { setAttributes({ content: val }); },
              placeholder:      __('Add tip content…', 'powerbi-insights'),
              allowedFormats:   ['core/bold', 'core/italic', 'core/link', 'core/code'],
            })
          )
        )
      );
    },

    save: function () {
      return null; // Server-side render
    },
  });

  /* ============================================================
     BLOCK 2: DAX Snippet
     ============================================================ */

  registerBlockType('powerbi-insights/dax-snippet', {
    title:       __('DAX Snippet', 'powerbi-insights'),
    description: __('A styled DAX code block with syntax coloring and copy button.', 'powerbi-insights'),
    category:    'powerbi-insights',
    icon:        'editor-code',
    supports:    { html: false, align: ['wide'] },
    attributes: {
      code:        { type: 'string', default: '' },
      label:       { type: 'string', default: 'DAX' },
      description: { type: 'string', default: '' },
    },

    edit: function (props) {
      const { attributes, setAttributes } = props;
      const { code, label, description } = attributes;

      const blockProps = useBlockProps({ className: 'code-block-wrap' });

      return el(
        Fragment,
        null,
        el(
          InspectorControls,
          null,
          el(
            PanelBody,
            { title: __('Code Settings', 'powerbi-insights'), initialOpen: true },
            el(TextControl, {
              label:    __('Language Label', 'powerbi-insights'),
              value:    label,
              onChange: function (val) { setAttributes({ label: val }); },
            }),
            el(TextControl, {
              label:    __('Description (optional)', 'powerbi-insights'),
              value:    description,
              onChange: function (val) { setAttributes({ description: val }); },
            })
          )
        ),
        el(
          'div',
          blockProps,
          description && el('p', { style: { fontSize: '.875rem', color: 'var(--clr-text-muted)', marginBottom: '.5rem' } }, description),
          el(
            'div',
            { className: 'code-block-header' },
            el('span', { className: 'code-lang' }, label),
            el('span', { style: { fontSize: '.75rem', color: '#aaa' } }, __('Copy', 'powerbi-insights'))
          ),
          el(
            'pre',
            null,
            el(
              RichText,
              {
                tagName:        'code',
                value:          code,
                onChange:       function (val) { setAttributes({ code: val }); },
                placeholder:    __('// Enter your DAX measure here…\nTotal Sales = SUM(Sales[Amount])', 'powerbi-insights'),
                className:      'language-dax',
                allowedFormats: [],
                preserveWhiteSpace: true,
              }
            )
          )
        )
      );
    },

    save: function () {
      return null; // Server-side render
    },
  });

  /* ============================================================
     BLOCK 3: Power BI Report Embed
     ============================================================ */

  registerBlockType('powerbi-insights/pbi-embed', {
    title:       __('Power BI Embed', 'powerbi-insights'),
    description: __('Embed a responsive Power BI report with a branded header.', 'powerbi-insights'),
    category:    'powerbi-insights',
    icon:        'chart-bar',
    supports:    { html: false, align: ['wide', 'full'] },
    attributes: {
      embedUrl:    { type: 'string', default: '' },
      reportTitle: { type: 'string', default: '' },
      showHeader:  { type: 'boolean', default: true },
    },

    edit: function (props) {
      const { attributes, setAttributes } = props;
      const { embedUrl, reportTitle, showHeader } = attributes;

      const blockProps = useBlockProps({ className: 'pbi-embed-wrap' });

      const isValid = embedUrl &&
        (embedUrl.includes('app.powerbi.com') || embedUrl.includes('msit.powerbi.com'));

      return el(
        Fragment,
        null,
        el(
          InspectorControls,
          null,
          el(
            PanelBody,
            { title: __('Embed Settings', 'powerbi-insights'), initialOpen: true },
            el(TextControl, {
              label:    __('Power BI Embed URL', 'powerbi-insights'),
              help:     __('Paste the embed URL from Power BI → Share → Embed report → Website or portal', 'powerbi-insights'),
              value:    embedUrl,
              onChange: function (val) { setAttributes({ embedUrl: val }); },
            }),
            el(TextControl, {
              label:    __('Report Title', 'powerbi-insights'),
              value:    reportTitle,
              onChange: function (val) { setAttributes({ reportTitle: val }); },
            }),
            el(ToggleControl, {
              label:    __('Show Header Bar', 'powerbi-insights'),
              checked:  showHeader,
              onChange: function (val) { setAttributes({ showHeader: val }); },
            })
          )
        ),
        el(
          'div',
          blockProps,
          showHeader && el(
            'div',
            { className: 'pbi-embed-header' },
            el('div', { className: 'pbi-embed-logo', 'aria-hidden': 'true' }, 'PBI'),
            el('span', { className: 'pbi-embed-title' }, reportTitle || __('Power BI Report', 'powerbi-insights'))
          ),
          isValid
            ? el(
                'div',
                { className: 'pbi-embed-container' },
                el('iframe', {
                  title: reportTitle || __('Power BI Report', 'powerbi-insights'),
                  src:   embedUrl,
                  frameBorder: '0',
                  allowFullScreen: true,
                })
              )
            : el(
                'div',
                {
                  style: {
                    padding: '3rem 2rem',
                    textAlign: 'center',
                    background: 'var(--clr-surface-2)',
                    color: 'var(--clr-text-muted)',
                    borderTop: showHeader ? '1px solid var(--clr-border)' : 'none',
                  },
                },
                el('div', { style: { fontSize: '2.5rem', marginBottom: '.75rem' } }, '📊'),
                el('p', null, __('Paste a Power BI embed URL in the sidebar to preview the report.', 'powerbi-insights'))
              )
        )
      );
    },

    save: function () {
      return null; // Server-side render
    },
  });

})(
  window.wp.blocks,
  window.wp.element,
  window.wp.blockEditor,
  window.wp.components,
  window.wp.i18n
);
