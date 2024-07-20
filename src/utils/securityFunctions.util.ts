import { cipher, random, util } from 'node-forge'

export function encryptData(encrypterLocal: cipher.BlockCipher | undefined, dataToEncrypt: string): string {
	const iv = random.getBytesSync(16) // genera un codigo random de 16 byte IV

	if (encrypterLocal) {
		encrypterLocal.start({ iv: iv })
		encrypterLocal.update(util.createBuffer(dataToEncrypt, 'utf8'))
		encrypterLocal.finish()

		return util.encode64(iv + encrypterLocal.output.data)
	}

	return ''
}

export function decryptData(decrypterLocal: cipher.BlockCipher | undefined, dataToDecrypt: string): any {
	const encryptedMsg = util.decode64(dataToDecrypt)

	const iv = encryptedMsg.substring(0, 16)

	const encrypted = encryptedMsg.substring(16)

	if (decrypterLocal) {
		decrypterLocal.start({ iv })
		decrypterLocal.update(util.createBuffer(encrypted))
		decrypterLocal.finish()

		return decrypterLocal.output.data
	}

	return ''
}
