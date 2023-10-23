// import logo from './logo.svg';
import './App.css';

import React, {useState, useRef, useEffect} from 'react';
import ToDoList from './ToDoList'
import Banner from './Banner'
import {v4 as uuidv4} from 'uuid'

const LOCAL_STORAGE_KEY = 'todoApp.todos'

function App() {

  const [todos, setTodos] = useState([])
  const toDoNameRef = useRef()

  useEffect(() => {
    document.title = "React-demo";
  }, []);

  useEffect(() => {
    const storedTodos = JSON.parse(localStorage.getItem(LOCAL_STORAGE_KEY))
    if(storedTodos) setTodos(prevTodos => [...prevTodos, ...storedTodos])
  }, [])

  useEffect(()=> {
    localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(todos))
  }, [todos])

  function toggleToDo(id) {
    const newTodos = [...todos]
    const todo = newTodos.find(todo => todo.id === id)
    todo.complete = !todo.complete
    setTodos(prevTodos => [...prevTodos, ...newTodos])
  }

  function handleAddToDo(e) {
    const name = toDoNameRef.current.value
    if(name === '') return
    setTodos(prevTodos => {
      return [...prevTodos, {id: uuidv4(), name: name, complete: false}]
    })
    toDoNameRef.current.value = null
  }

  function handleClearTodos() {
    const newTodos = todos.filter(todo => !todo.complete)
    setTodos(newTodos)
  }

  return (
    <>
    <Banner />
    <div className="App"></div>
    <div style={{margin: '20px'}}>
      <h1>To-do list</h1>
      <ToDoList todos={todos} toggleToDo={toggleToDo}/>
      <input ref={toDoNameRef} type="text" />
      <button onClick={handleAddToDo}> + </button> 
      <button onClick={handleClearTodos}> Clear Completed</button>
      <div>{todos.filter(todo => !todo.complete).length} left to do</div>
      <br /><br />

      <h3 style={{textAlign: 'center'}}>Lorem ipsum</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Eget velit aliquet sagittis id consectetur purus ut. Integer feugiat scelerisque varius morbi. At volutpat diam ut venenatis tellus in metus vulputate. Cursus risus at ultrices mi tempus. Aliquam nulla facilisi cras fermentum. Quis lectus nulla at volutpat diam ut venenatis tellus. Purus faucibus ornare suspendisse sed nisi. Bibendum at varius vel pharetra vel turpis. Neque vitae tempus quam pellentesque nec. Ut faucibus pulvinar elementum integer enim neque volutpat ac tincidunt. Sagittis aliquam malesuada bibendum arcu vitae elementum curabitur vitae. Suspendisse interdum consectetur libero id faucibus. Adipiscing elit pellentesque habitant morbi tristique senectus et netus. Platea dictumst quisque sagittis purus sit amet. Vitae auctor eu augue ut lectus arcu.</p>
      <p>Adipiscing elit pellentesque habitant morbi tristique senectus. Rhoncus urna neque viverra justo nec ultrices dui sapien eget. Lacus sed viverra tellus in hac habitasse. Accumsan in nisl nisi scelerisque eu ultrices vitae. Ut diam quam nulla porttitor massa. Facilisi nullam vehicula ipsum a arcu. Enim tortor at auctor urna nunc id cursus metus. Ut eu sem integer vitae justo eget magna fermentum. Senectus et netus et malesuada fames ac turpis egestas sed. Nunc id cursus metus aliquam eleifend mi. Convallis aenean et tortor at risus viverra adipiscing at in. Sed augue lacus viverra vitae congue eu consequat ac. Neque egestas congue quisque egestas diam in arcu cursus euismod. Ut porttitor leo a diam sollicitudin. Maecenas pharetra convallis posuere morbi leo urna molestie at. Lorem ipsum dolor sit amet consectetur adipiscing elit pellentesque. Adipiscing vitae proin sagittis nisl rhoncus mattis rhoncus urna neque. Pharetra massa massa ultricies mi quis hendrerit. Hendrerit dolor magna eget est lorem.</p>
      <p>Quis lectus nulla at volutpat diam. Aenean euismod elementum nisi quis eleifend quam. Tellus at urna condimentum mattis pellentesque id nibh tortor id. Id interdum velit laoreet id donec ultrices. Leo integer malesuada nunc vel risus commodo viverra. Vel fringilla est ullamcorper eget nulla facilisi etiam. Tortor consequat id porta nibh venenatis cras. Pharetra diam sit amet nisl suscipit adipiscing bibendum. Sed egestas egestas fringilla phasellus faucibus scelerisque. Consequat id porta nibh venenatis cras sed felis eget velit.</p>
      <p>Nec feugiat nisl pretium fusce id velit ut tortor. Mi bibendum neque egestas congue quisque egestas diam. Lectus vestibulum mattis ullamcorper velit. Faucibus turpis in eu mi. Lacus laoreet non curabitur gravida arcu ac tortor. Faucibus pulvinar elementum integer enim neque volutpat. Mattis pellentesque id nibh tortor id. Adipiscing vitae proin sagittis nisl rhoncus mattis. Porttitor eget dolor morbi non arcu risus quis. Augue lacus viverra vitae congue eu consequat. Vel facilisis volutpat est velit egestas. Sed cras ornare arcu dui vivamus arcu. Vel turpis nunc eget lorem dolor sed viverra ipsum. Faucibus scelerisque eleifend donec pretium vulputate sapien. Praesent tristique magna sit amet. Duis at consectetur lorem donec massa sapien. Habitant morbi tristique senectus et netus et malesuada. Ut morbi tincidunt augue interdum velit euismod in pellentesque.</p>
      <p>Ut diam quam nulla porttitor. Elit ullamcorper dignissim cras tincidunt lobortis. Massa sapien faucibus et molestie ac. Dictum fusce ut placerat orci nulla pellentesque. Quis ipsum suspendisse ultrices gravida dictum fusce. Id consectetur purus ut faucibus pulvinar elementum integer enim neque. Etiam tempor orci eu lobortis elementum. Platea dictumst quisque sagittis purus sit amet. Est placerat in egestas erat imperdiet sed euismod. Pellentesque diam volutpat commodo sed egestas. Scelerisque purus semper eget duis. Sed velit dignissim sodales ut eu sem integer. Pellentesque pulvinar pellentesque habitant morbi tristique senectus et. Vulputate sapien nec sagittis aliquam.</p>
    </div>
    </>
  );
}

export default App;
