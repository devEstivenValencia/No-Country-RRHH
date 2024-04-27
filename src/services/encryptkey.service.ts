'use client'

import { APIROUTES } from '#/config/API.routes'
import axios from 'axios'
import { cipher, pki, util } from 'node-forge'

export async function encryptkeyService(keypair: pki.rsa.KeyPair | undefined, publicPemKey: string): Promise<any> {
	try {
		if (!keypair) throw new Error('No se pudieron generar las claves')

		const { data } = await axios.post(APIROUTES.ENCRYPT_KEY, { publicKey: publicPemKey })

		const encryptedServerKey: string = data.encryptedKey
		const toDecryptKey: string = util.decode64(encryptedServerKey)
		const decryptedServerKey: string = keypair.privateKey.decrypt(toDecryptKey)

		const encrypter = cipher.createCipher('AES-CBC', decryptedServerKey)
		const decrypter = cipher.createDecipher('AES-CBC', decryptedServerKey)
		const keyId: string = data.key_id

		return new Promise(resolve => {
			resolve({
				encrypter,
				decrypter,
				keyId // guarda el id de la llave del servidor
			})
		})
	} catch (reason: any) {
		const { message } = reason.response?.data || reason
		throw new Error(message)
	}
}
