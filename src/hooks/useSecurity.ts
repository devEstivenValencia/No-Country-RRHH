import { useEffect, useState } from "react";
import axios from 'axios';
import { cipher, pki, random, util } from "node-forge";

function useSecurity(){
    const [keypair, setKeypair] = useState<pki.rsa.KeyPair>();//claves publicas y privadas generadas en el navegador del usuario
    const [publicPemKey, setPublicPemKey] = useState<string>();//clave publica en formato pem para enviar al servidor
 
    const [keypairCreated, setKeypairCreated] = useState<boolean>(false);
    const [error, setError] = useState<string>('');

    useEffect(() => {
        generateKeyPair(0);
    },[]);

    function generateKeyPair(intent: number){
        if(intent < 3){
            console.log(intent);
            pki.rsa.generateKeyPair({bits: 2048, workers: 2},(err: Error,key: pki.rsa.KeyPair)=>{
                if(!err){
                    setKeypair(key);
                    setPublicPemKey(pki.publicKeyToPem(key.publicKey))
                    setKeypairCreated(true);
                }else{
                    generateKeyPair(intent++);
                }
            });
        }else{
            console.log('intent '+intent+': the key pair could not be created')
            setError('the key pair could not be created');
        }
    }

    function getEncryptionKey(url: string) {
        console.log('se ejecuta la promesa')
        return axios.post(url,{
            "publicKey": publicPemKey
        })
        .then(res => res.data)
        .then(res => {
            if(keypair){
                const encryptedServerKey: string = res.encryptedKey;
                const toDecryptKey:string = util.decode64(encryptedServerKey);
                const decryptedServerKey: string = keypair.privateKey.decrypt(toDecryptKey);
                
                const encrypter = cipher.createCipher('AES-CBC', decryptedServerKey);
                const decrypter = cipher.createDecipher('AES-CBC', decryptedServerKey);
                
                return {
                    encrypter,
                    decrypter,
                    idkey : res.id_key//guarda el id de la llave del servidor
                }
            }else{/* 
                reject('keypair not created'); */
                return Promise.reject('keypair not created');
            }
        })
    };

    return {error,keypairCreated,getEncryptionKey};
}

export default useSecurity;