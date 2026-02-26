import { useNavigate } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";
import { Button } from "../ui";

export default function Header() {
	const navigate = useNavigate();
	const { logout } = useAuth();

	const handleLogout = () => {
		logout();
		navigate("/login");
	};

	return (
		<header className="navbar bg-neutral text-neutral-content shadow-md">
			<div className="flex-1">
				<h1 className="text-xl font-medium">CRM Admin Panel</h1>
			</div>
			<div className="flex-none">
				<Button variant="error" size="sm" onClick={handleLogout}>
					Wyloguj
				</Button>
			</div>
		</header>
	);
}
