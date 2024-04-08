import '#/styles/main.css'
import type { Metadata } from 'next'
import React from 'react'

// TODO: Completar metadata
export const metadata: Metadata = {
	title: '',
	description: ''
}

export default function RootLayout({
	children
}: Readonly<{
	children: React.ReactNode
}>) {
	return (
		<html lang='es'>
			<body>{children}</body>
		</html>
	)
}
