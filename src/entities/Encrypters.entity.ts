import { cipher } from 'node-forge'

export interface Encrypters {
	encrypter: cipher.BlockCipher
	decrypter: cipher.BlockCipher
	idKey: string
}
