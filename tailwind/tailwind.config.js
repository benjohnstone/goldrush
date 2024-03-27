// Set the Preflight flag based on the build target.
const includePreflight = 'editor' === process.env._TW_TARGET ? false : true;
const colors = require('tailwindcss/colors');

module.exports = {
	presets: [
		// Manage Tailwind Typography's configuration in a separate file.
		require('./tailwind-typography.config.js'),
	],
	content: [
		// Ensure changes to PHP files and `theme.json` trigger a rebuild.
		'./theme/**/*.php',
	],
	theme: {
		// Extend the default Tailwind theme.
		extend: {
			screens: {
				'sm': '640px',
				'md': '768px',
				'lg': '1024px',
				'xl': '1280px',
				'2xl': '1536px',
			},	
			colors: colors,
			colors: {
				primary: {
					DEFAULT: '#8CC541',
					50: '#E1F0CE',
					100: '#D8EBBE',
					200: '#C5E29F',
					300: '#B2D880',
					400: '#9FCF60',
					500: '#8CC541',
					600: '#6E9E30',
					700: '#507323',
					800: '#324816',
					900: '#141D09',
					950: '#050702'
				},
				'concrete': {
					DEFAULT: '#F7F7F7',
					50: '#FFFFFF',
					100: '#FFFFFF',
					200: '#FFFFFF',
					300: '#FFFFFF',
					400: '#FFFFFF',
					500: '#F7F7F7',
					600: '#DBDBDB',
					700: '#BFBFBF',
					800: '#A3A3A3',
					900: '#878787',
					950: '#797979'
				},
				'thunder': {
					DEFAULT: '#241F20',
					50: '#877478',
					100: '#7C6B6E',
					200: '#66585A',
					300: '#504547',
					400: '#3A3233',
					500: '#241F20',
					600: '#060505',
					700: '#000000',
					800: '#000000',
					900: '#000000',
					950: '#000000'
				  },
			},
		},
		listStyleType: {
			none: 'none',
			disc: 'disc',
			decimal: 'decimal',
			square: 'square',
			roman: 'upper-roman',
			image: 'image',
		  },
		spacing: {
			'0': '0px',
			'px': '1px',
			'0.5': '2px',
			'1': '4px',
			'1.5': '6px',
			'2': '8px',
			'2.5': '10px',
			'3': '12px',
			'3.5': '14px',
			'4': '16px',
			'5': '20px',
			'6': '24px',
			'7': '28px',
			'8': '32px',
			'9': '36px',
			'10': '40px',
			'11': '44px',
			'12': '48px',
			'14': '56px',
			'16': '64px',
			'20': '80px',
			'24': '96px',
			'28': '112px',
			'32': '128px',
			'36': '144px',
			'40': '160px',
			'44': '176px',
			'48': '192px',
			'52': '208px',
			'56': '224px',
			'60': '240px',
			'64': '256px',
			'72': '288px',
			'80': '320px',
			'96': '384px',


		},
		screens: {
			'sm': '640px',
			'md': '833px',
			'lg': '1024px',
			'xl': '1440px',
			'2xl': '1920px',
		},
		fontFamily: {
			'sans': ['Roboto Condensed', 'sans-serif'],
			'serif': ['Noto Serif', 'serif'],
			'mono': ['Passion One', 'sans-serif'],
			'material': ['Material Symbols Outlined'],
		},
		fontSize: {
			sm: '16px',
			base: '20px',
			lg: '24px',
			xl: '30px',
			'2xl': '36px',
			'3xl': '44px',
			'4xl': '3.5rem',
			'5xl': '4rem',
			'6xl': '5rem',
		},
		aspectRatio: {
			'video': '16 / 9',
			'postthumb': '4 / 3',
			'square': '1 / 1',
			'none': 'none',
		},
		keyframes: {
			pulseOpacity: {
				'0%, 100%': { opacity: '0.5' },
				'50%': { opacity: '1' },
			}
		},
		animation: {
			pulseOpacity: 'pulseOpacity 1s ease-in-out infinite',
		}
	},
	corePlugins: {
		// Disable Preflight base styles in builds targeting the editor.
		preflight: includePreflight,
	},
	plugins: [
		// Add Tailwind Typography (via _tw fork).
		require('@_tw/typography'),

		// Extract colors and widths from `theme.json`.
		require('@_tw/themejson'),

		// Uncomment below to add additional first-party Tailwind plugins.
		// require('@tailwindcss/forms'),
		require('@tailwindcss/aspect-ratio'),
		// require('@tailwindcss/container-queries'),

		require('flowbite/plugin'),

		require('gsap'),
	],
	safelist: [
		'no-underline',
		'relative',
		'absolute',
		'whitespace-nowrap',
		'flex-row-reverse',
		'items-stretch',
		'self-stretch',
		'max-w-none',
		'bg-contain',
		'rounded-full',
		'flex',
		'inline-flex',
		'pointer-events-none',
		'whitespace-nowrap',
		'lg:hidden',
		{ pattern: /self-./ },
		{ pattern: /overflow-./ },
		{ pattern: /gap-./ },
		{ pattern: /z-./ },
		{ pattern: /grid-./ },
		{ pattern: /columns-./ },
		{ pattern: /max-w-./ },
		{ pattern: /opacity-./ },
		{ pattern: /rounded-./ },
		{ pattern: /rotate-./ },
		{ pattern: /-rotate-./ },
		{ pattern: /translate-./ },
		{ pattern: /-translate-./ },
		{ pattern: /bg-opacity-./ },
		{ pattern: /left-./ },
		{ pattern: /top-./ },
		{ pattern: /bottom-./ },
		{ pattern: /right-./ },
		{ pattern: /-mt-./ },
		{ pattern: /-mb-./ },
		{ pattern: /-ml-./ },
		{ pattern: /-mr-./ },
		{ pattern: /ml-./ },
		{ pattern: /mr-./ },
		{ pattern: /mt-./ },
		{ pattern: /mb-./ },
		{ pattern: /py-./ },
		{ pattern: /col-span-./ },
		{ pattern: /row-span-./ },
		{ pattern: /w-./ },
		{ pattern: /h-./ },
	],
};
