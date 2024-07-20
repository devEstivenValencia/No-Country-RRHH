import { NextRequest, NextResponse } from 'next/server'

import { APIROUTES } from './config/API.routes'
import { APPROUTES } from './config/APP.routes'

export async function middleware(request: NextRequest) {
	const { pathname } = request.nextUrl
	// console.log('middleware ejecutado en:', '\n', pathname)

	try {
		const session = request.cookies.get('session')
		console.log('cokies ', session)
		if (session) {
			if (pathname === APPROUTES.LOGIN || pathname === APPROUTES.REGISTER) {
				console.error('se va al dashboard')

				const type = request.cookies.get('type')?.value || 'employee'

				if (type === 'employee') return NextResponse.redirect(new URL(APPROUTES.WORKER, request.url))

				return NextResponse.redirect(new URL(APPROUTES.DASHBOARD, request.url))
			}
			const { value: token } = session

			await fetch(APIROUTES?.SESSION?.VALIDATE, {
				method: 'POST',
				headers: {
					Authorization: `Bearer ${token}`
				},
				body: JSON.stringify({ session: token })
			}).then(({ status }) => {
				if (status !== 200) throw new Error('Sesion no encontrada en la DB')
			})

			return NextResponse.next()
		}

		throw new Error('Sesion no encontrada en el Navegador')
	} catch (reason: any) {
		console.error('❗', 'Middleware de sesion fallo:', '\n', reason)
		if (pathname === APPROUTES.LOGIN || pathname === APPROUTES.REGISTER || pathname === APPROUTES.HOME)
			return NextResponse.next()

		const NewNextResponse: any = NextResponse

		const response = new NewNextResponse(new Blob(), {
			cookies: request.cookies,
			status: 307,
			headers: { location: String(new URL(APPROUTES.LOGIN, request.url)) }
		})
		response.cookies.delete('session')
		return response
	}
}

export const config = {
	/*
	 * Coincide con todas las rutas de petición excepto las que empiezan por:
	 * - api (rutas API)
	 * _next/static (archivos estáticos)
	 * _next/image (archivos de optimización de imágenes)
	 * favicon.ico (archivo favicon)
	 * Se excluyen todos los archivos ubicados en public
	 */
	matcher: ['/', '/((?!api|_next/static|_next/image|images|icons|fonts|videos|audios|favicon.ico).*)']
}
