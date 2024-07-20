'use client'

import { pki } from 'node-forge'
import { useEffect, useState } from 'react'

function useSecurity(): any {
	const [keypair, setKeypair] = useState<pki.rsa.KeyPair>() // claves publicas y privadas generadas en el navegador del usuario
	const [publicPemKey, setPublicPemKey] = useState<string>() // clave publica en formato pem para enviar al servidor

	const [keypairCreated, setKeypairCreated] = useState<boolean>(false)
	const [error, setError] = useState<string>('')

	useEffect(() => {
		window.setTimeout(() => {
			pki.rsa.generateKeyPair({ bits: 2048, workers: 2 }, (err: Error, key: pki.rsa.KeyPair) => {
				if (!err) {
					setKeypair(key)
					setPublicPemKey(pki.publicKeyToPem(key.publicKey))
					setKeypairCreated(true)
				} else {
					setError('error al crear key')
				}
			})
		}, 100)
	}, [])

	return { error, keypairCreated, keypair, publicPemKey }
}

export default useSecurity
