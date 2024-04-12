import { useEffect } from 'react'

export function Loader() {
	useEffect(() => {
		async function getLoader() {
			const { ring } = await import('ldrs')
			ring.register()
		}
		getLoader()
	}, [])
	return <l-ring size='20' stroke='2' bg-opacity='0' speed='2' color='black'></l-ring>
}
