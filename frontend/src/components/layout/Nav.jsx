import { Link, useLocation } from "react-router-dom";

export default function Nav() {
	const location = useLocation();

	const isActive = (path) => location.pathname === path || location.pathname.startsWith(path);

	return (
		<nav className="bg-base-100 border-b border-base-300 shadow-sm">
			<ul className="menu menu-horizontal px-8">
				<li>
					<Link to="/dashboard" className={isActive("/dashboard") ? "active" : ""}>
						Dashboard
					</Link>
				</li>
				<li>
					<Link to="/users" className={isActive("/users") ? "active" : ""}>
						Użytkownicy
					</Link>
				</li>
			</ul>
		</nav>
	);
}
