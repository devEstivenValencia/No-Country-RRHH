import { KeyWithValue } from '#/types/KeyWithValue.type'

export const APPROUTES: KeyWithValue<string> = {
	HOME: '/',
	DASHBOARD: '/app',
	LOGIN: '/login',
	REGISTER: '/register'
} as const
