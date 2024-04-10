import type { Config } from 'tailwindcss'

const config = {
	darkMode: ['class'],
	content: ['./pages/**/*.{ts,tsx}', './components/**/*.{ts,tsx}', './app/**/*.{ts,tsx}', './src/**/*.{ts,tsx}'],
	prefix: '',
	theme: {
		container: {
			center: true,
			padding: '2rem',
			screens: {
				'2xl': '1400px'
			}
		},
		colors: {
			primary: {
				'50': '#f6f5fd',
				'100': '#efedfa',
				'200': '#e0def6',
				'300': '#c9c3ef',
				'400': '#ada0e5',
				'500': '#907ad8',
				'600': '#7552c7',
				'700': '#6d4ab7',
				'800': '#5b3e99',
				'900': '#4c347e',
				'950': '#2f2055'
			},
			secondary: {
				'50': '#f0fdf9',
				'100': '#cdfaed',
				'200': '#9af5db',
				'300': '#5be7c4',
				'400': '#30d1ae',
				'500': '#17b595',
				'600': '#0f927a',
				'700': '#117464',
				'800': '#125d51',
				'900': '#144d44',
				'950': '#052e29'
			},
			neutro: {
				'50': '#f6f6f7',
				'100': '#efeff0',
				'200': '#e2e2e3',
				'300': '#cfd0d2',
				'400': '#bbbbbe',
				'500': '#a8a8ac',
				'600': '#8e8e93',
				'700': '#7f7f83',
				'800': '#68686b',
				'900': '#565659',
				'950': '#323234'
			},
			green: {
				'50': '#f1fcf3',
				'100': '#dff9e5',
				'200': '#c0f2cc',
				'300': '#8fe6a4',
				'400': '#57d175',
				'500': '#34c759',
				'600': '#22973f',
				'700': '#1e7735',
				'800': '#1d5e2e',
				'900': '#194e28',
				'950': '#082b13'
			},
			red: {
				'50': '#fff2f1',
				'100': '#ffe1df',
				'200': '#ffc8c5',
				'300': '#ffa29d',
				'400': '#ff6c64',
				'500': '#ff3b30',
				'600': '#ed2015',
				'700': '#c8170d',
				'800': '#a5170f',
				'900': '#881a14',
				'950': '#4b0804'
			},
			yellow: {
				'50': '#fffee7',
				'100': '#fffec1',
				'200': '#fff886',
				'300': '#ffec41',
				'400': '#ffdb0d',
				'500': '#ffcc00',
				'600': '#d19500',
				'700': '#a66a02',
				'800': '#89530a',
				'900': '#74430f',
				'950': '#442304'
			},
			blue: {
				'50': '#edfaff',
				'100': '#d6f2ff',
				'200': '#b5eaff',
				'300': '#83dfff',
				'400': '#48cbff',
				'500': '#1eacff',
				'600': '#068eff',
				'700': '#007aff',
				'800': '#085dc5',
				'900': '#0d519b',
				'950': '#0e315d'
			}
		},
		extend: {
			keyframes: {
				'accordion-down': {
					from: { height: '0' },
					to: { height: 'var(--radix-accordion-content-height)' }
				},
				'accordion-up': {
					from: { height: 'var(--radix-accordion-content-height)' },
					to: { height: '0' }
				}
			},
			animation: {
				'accordion-down': 'accordion-down 0.2s ease-out',
				'accordion-up': 'accordion-up 0.2s ease-out'
			}
		}
	},
	plugins: [require('tailwindcss-animate')]
} satisfies Config

export default config
