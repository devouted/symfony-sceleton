import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import { useAuth } from "./context/AuthContext";
import Login from "./pages/Login";
import Dashboard from "./pages/Dashboard";
import Users from "./pages/Users";
import UserNew from "./pages/UserNew";
import UserEdit from "./pages/UserEdit";
import ProtectedRoute from "./components/ProtectedRoute";

function App() {
	const { isAuthenticated } = useAuth();

	return (
		<Router>
			<Routes>
				<Route 
					path="/login" 
					element={isAuthenticated ? <Navigate to="/dashboard" replace /> : <Login />} 
				/>
				<Route 
					path="/dashboard" 
					element={<ProtectedRoute><Dashboard /></ProtectedRoute>} 
				/>
				<Route 
					path="/users" 
					element={<ProtectedRoute><Users /></ProtectedRoute>} 
				/>
				<Route 
					path="/users/new" 
					element={<ProtectedRoute><UserNew /></ProtectedRoute>} 
				/>
				<Route 
					path="/users/:id/edit" 
					element={<ProtectedRoute><UserEdit /></ProtectedRoute>} 
				/>
				<Route path="/" element={<Navigate to="/dashboard" replace />} />
			</Routes>
		</Router>
	);
}

export default App;
