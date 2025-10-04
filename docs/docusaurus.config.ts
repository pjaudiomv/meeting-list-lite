import {themes as prismThemes} from 'prism-react-renderer';
import type {Config} from '@docusaurus/types';
import type * as Preset from '@docusaurus/preset-classic';

// This runs in Node.js - Don't use client-side code here (browser APIs, JSX...)

const config: Config = {
  title: 'Meeting List Lite',
  tagline: 'A streamlined WordPress plugin for displaying 12-step meeting information',
  favicon: 'img/favicon.ico',

  // Future flags, see https://docusaurus.io/docs/api/docusaurus-config#future
  future: {
    v4: true, // Improve compatibility with the upcoming Docusaurus v4
  },

  // Set the production url of your site here
  url: 'https://mll.pjbuilds.dev',
  // Set the /<baseUrl>/ pathname under which your site is served
  // For GitHub pages deployment, it is often '/<projectName>/'
  baseUrl: process.env.DOCUSAURUS_LOCAL_DEV ? '/' : '/meeting-list-lite/',

  // GitHub pages deployment config.
  // If you aren't using GitHub pages, you don't need these.
  organizationName: 'pjaudiomv', // Usually your GitHub org/user name.
  projectName: 'meeting-list-lite', // Usually your repo name.

  onBrokenLinks: 'warn',

  // Even if you don't use internationalization, you can use this field to set
  // useful metadata like html lang. For example, if your site is Chinese, you
  // may want to replace "en" with "zh-Hans".
  i18n: {
    defaultLocale: 'en',
    locales: ['en'],
  },

  presets: [
    [
      'classic',
      {
        docs: {
          sidebarPath: './sidebars.ts',
          // Please change this to your repo.
          // Remove this to remove the "edit this page" links.
          editUrl:
            'https://github.com/pjaudiomv/meeting-list-lite/tree/main/docs/',
        },
        blog: false, // Disable blog for plugin documentation
        theme: {
          customCss: './src/css/custom.css',
        },
      } satisfies Preset.Options,
    ],
  ],

  themeConfig: {
    // Replace with your project's social card
    image: 'img/docusaurus-social-card.jpg',
    colorMode: {
      respectPrefersColorScheme: true,
    },
    navbar: {
      title: 'Meeting List Lite',
      logo: {
        alt: 'Meeting List Lite Logo',
        src: 'img/logo.svg',
      },
      items: [
        {
          type: 'docSidebar',
          sidebarId: 'tutorialSidebar',
          position: 'left',
          label: 'Documentation',
        },
        {
          to: '/api',
          label: 'API Docs',
          position: 'left',
        },
        {
          href: 'https://wordpress.org/plugins/meeting-list-lite/',
          label: 'WordPress Plugin',
          position: 'right',
        },
        {
          href: 'https://github.com/pjaudiomv/meeting-list-lite',
          label: 'GitHub',
          position: 'right',
        },
      ],
    },
    footer: {
      style: 'dark',
      links: [
        {
          title: 'Documentation',
          items: [
            {
              label: 'Getting Started',
              to: '/docs/intro',
            },
            {
              label: 'Installation',
              to: '/docs/installation',
            },
            {
              label: 'Usage',
              to: '/docs/usage',
            },
            {
              label: 'API Reference',
              to: '/api',
            },
          ],
        },
        {
          title: 'Community',
          items: [
            {
              label: 'WordPress Plugin Directory',
              href: 'https://wordpress.org/plugins/meeting-list-lite/',
            },
            {
              label: 'BMLT Project',
              href: 'https://bmlt.app/',
            },
            {
              label: 'Code4Recovery',
              href: 'https://code4recovery.org/',
            },
          ],
        },
        {
          title: 'More',
          items: [
            {
              label: 'GitHub',
              href: 'https://github.com/pjaudiomv/meeting-list-lite',
            },
            {
              label: 'Issues',
              href: 'https://github.com/pjaudiomv/meeting-list-lite/issues',
            },
          ],
        },
      ],
      copyright: `Copyright © ${new Date().getFullYear()} pjaudiomv. Licensed under GPL v2+.`,
    },
    prism: {
      theme: prismThemes.github,
      darkTheme: prismThemes.dracula,
    },
  } satisfies Preset.ThemeConfig,
};

export default config;
