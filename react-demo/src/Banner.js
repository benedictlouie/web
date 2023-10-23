import React, {useState} from 'react'
import './Banner.css';


export default function Banner() {

    const [isOpen, setIsOpen] = useState(false);

    return (
        <>
        <div id="navBar">
            <span>React-Demo</span>
            <div id="items" className={isOpen && 'open'}>
                <a href="#">Home</a>
                <a href="#">About</a>
                <a href="#">Services</a>
                <a href="#">Contact</a>
            </div>
            <div id="navToggle" className={isOpen && 'open'} onClick={() => setIsOpen(!isOpen)}>
                <div id="bar"></div>
            </div>
        </div>
        <div id="banner"></div>
        </>
    )
} 
