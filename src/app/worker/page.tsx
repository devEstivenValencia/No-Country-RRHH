'use client'

import Script from 'next/script'
import { Icon, Typography, Button } from "#/components";
import { APPROUTES } from "#/config/APP.routes";
import Image from "next/image";
import Link from "next/link";
import Cookies from 'js-cookie'
import { useRouter } from "next/navigation";
import { Dialog, DialogContent, DialogDescription, DialogTitle, DialogTrigger } from "#/components/ui/dialog";
import { SendVacationService } from "#/services";

export default function WorkerPage () {

    const router = useRouter()

    function onClick(){
		Cookies.remove('session')
		localStorage.removeItem('user')
        router.push(APPROUTES.LOGIN)
    }

    async function sendForm(){
        const initialDate: string = document.getElementById('initial-date')?.getAttribute('value') || ''
        const finalDate: string = document.getElementById('final-date')?.getAttribute('value') || ''
        await SendVacationService(initialDate.split("/").reverse().join("-"),finalDate.split("/").reverse().join("-"))
            .then((data) => {})
            .catch(({ message }) => console.log(message))
    }
    
    return (
        <section className="bg-secondary-50 min-h-screen h-full min-w-fit w-full">
            <nav className="flex justify-between bg-[#FFFFFF] h-20 items-center fixed top-0 left-0 right-0 shadow-md">
				<Link href={APPROUTES.WORKER}>
                    <Image
			            src='/images/green-logo-image.png'
			            alt='Logo'
			            width={50}
			            height={50}
			            className='m-3 ml-10'
			        />
                </Link>
				<Dialog>
                    <DialogTrigger className="flex gap-2 text-neutro-800 hover:text-secondary-500 mr-10 border border-secondary-500 rounded-3xl p-2 px-3">
                        <Icon name="sign-out-alt"></Icon>
                        Cerrar sesión
                    </DialogTrigger>
                    <DialogContent className="bg-[#FFFFFF] border-red-500 rounded-3xl">
                            <DialogTitle className="text-neutro-800">Estás a punto de cerrar sesión...</DialogTitle>
                            <DialogDescription className="text-neutro-800">¿Quieres seguir adelante con esta acción?</DialogDescription>
                            <Button onClick={onClick} className='border border-red-500 text-red-500 hover:bg-red-500 hover:text-neutro-50 rounded-3xl'>
                                Aceptar
                            </Button>
                    </DialogContent>
                </Dialog>
			</nav>
            
            <Typography as="h3" className="pt-28 pl-10 text-secondary-500 font-bold">Nombre de la empresa</Typography>
            
            <section className="flex flex-col justify-center items-center mt-10">
                <div className="bg-[#FFFFFF] md:min-w-[900px] md:min-h-[500px] rounded-3xl shadow-md mb-20">
                <div className=" p-10 ">
                        <Typography as="h4" className="text-secondary-500 font-semibold">Selección de Fechas de Vacaciones</Typography>
                        <Typography as="p" className="text-neutro-800 w-[600px]">Este módulo te permite seleccionar las fechas en las que desea tomar sus vacaciones. Simplemente elija las fechas de inicio y fin de su período de descanso y envíe la solicitud. Una vez enviada, su solicitud de vacaciones será enviada al departamento correspondiente para su aprobación.</Typography>
                    </div>
                    <div id="calendario" className=''></div>
                    <div className='flex justify-center'>
                        <Button onClick={()=>sendForm()} className='bg-secondary-500 rounded-3xl text-neutro-50 w-20 flex justify-center my-10 shadow-md'>Enviar</Button>
                    </div>
                    
                </div>
            </section>
            <Script src="calendario.js" />
        </section>
    )
}