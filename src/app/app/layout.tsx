'use client'

import { Button, Icon, Typography } from "#/components";
import { APPROUTES } from "#/config/APP.routes";
import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import Cookies from 'js-cookie'
import { useRouter } from "next/navigation";

export default function Layout ({children} : {children: React.ReactNode}) {

    const [openMenu, setOpenMenu] = useState(false)
    const handleOpenMenu = () => setOpenMenu(!openMenu)
	const router = useRouter()

    function onClick(){
		Cookies.remove('session')
		localStorage.removeItem('user')
        router.push(APPROUTES.LOGIN)
    }

    return (
        <section className="flex flex-col md:flex-row">
            <div>
                <div className="bg-primary-50">
                <Button onClick={handleOpenMenu} type="button" className='text-primary-500 md:hidden bg-[#FFFFFF] w-10 h-10 shadow-md relative top-12 left-10'>
                    {openMenu ? <Icon name="cross-small"></Icon> : <Icon name="menu-burger"></Icon>}
				</Button>
                </div>
                
                <nav className="hidden md:block">
                    <div className="flex w-full h-screen flex-col justify-between">
                        <div className="p-4 gap-4 w-full">
                            <div className="flex flex-col items-center">
                                <Link href={APPROUTES.DASHBOARD}>
                                    <Image
			        	    	        src='/images/purple-logo-image.png'
			        	    	        alt='Logo'
			        	    	        width={150}
			        	    	        height={150}
			        	    	        className='m-3'
			        	            />
                                </Link>
                            </div>

                            <Typography as='p' className="text-neutro-500 font-bold p-1">Navegación</Typography>
                            <ul className="flex flex-col">
                                <li>
                                    <Link href={APPROUTES.DASHBOARD} className="hover:bg-primary-500 hover:text-neutro-50 text-neutro-950 hover:font-bold flex flex-row h-10 p-2 gap-3 rounded-md md:w-52">
                                        <Icon name="home"></Icon>
                                        Inicio
                                    </Link>
                                </li>
                                <li>
                                    <Link href={APPROUTES.EMPLOYEES} className="hover:bg-primary-500 hover:text-neutro-50 text-neutro-950 hover:font-bold flex flex-row h-10 p-2 gap-3 rounded-md md:w-52">
                                        <Icon name="user"></Icon>
                                        Empleado
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div className="p-4 gap-4 w-full">
                            <ul className="flex flex-col">
                                <li className="hover:bg-primary-50 text-neutro-950 flex flex-row h-10 p-2 gap-3 rounded-md md:w-52">
                                    <Icon name="settings"></Icon>
                                    <Link href=''>Configuración</Link>
                                </li>
                                <li>
                                    <Button onClick={onClick} className='hover:bg-primary-50 text-neutro-950 font-normal w-full h-10 p-2 gap-3 text-base rounded-md justify-start md:w-52'>
                                        <Icon name="sign-out-alt"></Icon>
                                        Cerrar sesión
			        				</Button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                {openMenu && (
                    <nav className="block">
                    <div className="flex w-full h-screen flex-col justify-between">
                        <div className="p-4 gap-4 w-full">
                            <div className="flex flex-col items-center">
                                <Link href=''>
                                    <Image
			        	    	        src='/images/purple-logo-image.png'
			        	    	        alt='Logo'
			        	    	        width={150}
			        	    	        height={150}
			        	    	        className='m-3'
			        	            />
                                </Link>
                            </div>

                            <Typography as='p' className="text-neutro-500 font-bold p-1">Navegación</Typography>
                            <ul className="flex flex-col">
                                <li className="hover:bg-primary-500 hover:text-neutro-50 flex flex-row h-10 p-2 gap-3 rounded-md md:w-52">
                                    <Icon name="home"></Icon>
                                    <Link href='/app'>Inicio</Link>
                                </li>
                                <li className="hover:bg-primary-500 hover:text-neutro-50 flex flex-row h-10 p-2 gap-3 rounded-md md:w-52">
                                    <Icon name="user"></Icon>
                                    <Link href='/app/employees'>Empleado</Link>
                                </li>
                            </ul>
                        </div>
                        <div className="p-4 gap-4 w-full">
                            <ul className="flex flex-col">
                                <li className="hover:bg-primary-50 flex flex-row h-10 p-2 gap-3 rounded-md md:w-52">
                                    <Icon name="settings"></Icon>
                                    <Link href=''>Configuración</Link>
                                </li>
                                <li>
                                    <Button className='hover:bg-primary-50 w-full h-10 p-2 gap-3 text-base font-normal rounded-md justify-start md:w-52'>
                                        <Icon name="sign-out-alt"></Icon>
                                        Cerrar sesión
			        				</Button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                )}
            </div>
            <div className="flex-grow">{children}</div>
        </section>
    )
}