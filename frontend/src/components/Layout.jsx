import { Link, useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useState } from "react";

function Layout({ children }) {
	const location = useLocation();
	const navigate = useNavigate();
	const { logout } = useAuth();
	const [hoveredLink, setHoveredLink] = useState(null);

	const handleLogout = () => {
		logout();
		navigate("/login");
	};

	const getLinkStyle = (path, isHovered) => ({
		display: "block",
		padding: "1rem",
		textDecoration: "none",
		color: location.pathname === path || location.pathname.startsWith(path) ? "#007bff" : "#495057",
		fontWeight: location.pathname === path || location.pathname.startsWith(path) ? "600" : "normal",
		borderBottom: location.pathname === path || location.pathname.startsWith(path) ? "3px solid #007bff" : "3px solid transparent",
		backgroundColor: isHovered ? "#e9ecef" : "transparent",
		transition: "all 0.2s ease"
	});

	return (
		<div style={{ minHeight: "100vh", display: "flex", flexDirection: "column", backgroundColor: "#f8f9fa" }}>
			<header style={{ 
				backgroundColor: "#343a40", 
				color: "white", 
				padding: "1rem 2rem",
				boxShadow: "0 2px 4px rgba(0,0,0,0.1)",
				display: "flex",
				justifyContent: "space-between",
				alignItems: "center",
				flexWrap: "wrap",
				gap: "1rem"
			}}>
				<h1 style={{ margin: 0, fontSize: "1.5rem", fontWeight: "500" }}>CRM Admin Panel</h1>
				<button 
					onClick={handleLogout}
					style={{
						backgroundColor: "#dc3545",
						color: "white",
						border: "none",
						padding: "0.5rem 1rem",
						borderRadius: "0.25rem",
						cursor: "pointer",
						fontSize: "0.9rem",
						fontWeight: "400",
						transition: "background-color 0.15s ease-in-out"
					}}
					onMouseOver={(e) => e.target.style.backgroundColor = "#c82333"}
					onMouseOut={(e) => e.target.style.backgroundColor = "#dc3545"}
				>
					Wyloguj
				</button>
			</header>
			
			<nav style={{ 
				backgroundColor: "white", 
				borderBottom: "1px solid #dee2e6",
				padding: "0 2rem",
				boxShadow: "0 1px 2px rgba(0,0,0,0.05)"
			}}>
				<ul style={{ 
					listStyle: "none", 
					padding: 0, 
					margin: 0, 
					display: "flex", 
					gap: "0.5rem",
					flexWrap: "wrap"
				}}>
					<li>
						<Link 
							to="/dashboard" 
							style={getLinkStyle("/dashboard", hoveredLink === "dashboard")}
							onMouseEnter={() => setHoveredLink("dashboard")}
							onMouseLeave={() => setHoveredLink(null)}
						>
							Dashboard
						</Link>
					</li>
					<li>
						<Link 
							to="/users" 
							style={getLinkStyle("/users", hoveredLink === "users")}
							onMouseEnter={() => setHoveredLink("users")}
							onMouseLeave={() => setHoveredLink(null)}
						>
							Użytkownicy
						</Link>
					</li>
				</ul>
			</nav>
			
			<main style={{ 
				flex: 1, 
				padding: "2rem",
				maxWidth: "1200px",
				width: "100%",
				margin: "0 auto"
			}}>
				{children}
			</main>
		</div>
	);
}

export default Layout;
