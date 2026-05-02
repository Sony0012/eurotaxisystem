import { createContext, useContext, useState, useEffect, ReactNode } from "react";
import axios from "axios";

const API_BASE_URL = "https://eurotaxisystem.site/api";

interface User {
  id: string;
  email: string;
  name: string;
  role: string;
}

interface AuthContextType {
  user: User | null;
  token: string | null;
  isLoading: boolean;
  login: (loginIdentifier: string, password: string) => Promise<void>;
  signup: (formData: any) => Promise<void>;
  logout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const initAuth = async () => {
      const storedToken = localStorage.getItem("auth_token");
      const storedUser = localStorage.getItem("user");
      
      if (storedToken && storedUser) {
        setToken(storedToken);
        setUser(JSON.parse(storedUser));
        axios.defaults.headers.common["Authorization"] = `Bearer ${storedToken}`;
      }
      setIsLoading(false);
    };

    initAuth();

    const interceptor = axios.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          logout();
        }
        return Promise.reject(error);
      }
    );

    return () => {
      axios.interceptors.response.eject(interceptor);
    };
  }, []);

  const login = async (loginIdentifier: string, password: string) => {
    try {
      const response = await axios.post(`${API_BASE_URL}/login`, {
        login: loginIdentifier,
        password,
        device_name: "mobile_app"
      });

      if (response.data.success) {
        const { token, user } = response.data;
        localStorage.setItem("auth_token", token);
        localStorage.setItem("user", JSON.stringify(user));
        
        setToken(token);
        setUser(user);
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      } else {
        throw new Error(response.data.message || "Login failed");
      }
    } catch (error: any) {
      console.error("Login error:", error);
      throw error;
    }
  };

  const signup = async (formData: any) => {
    try {
      const response = await axios.post(`${API_BASE_URL}/register`, formData);
      if (!response.data.success) {
        throw new Error(response.data.message || "Signup failed");
      }
    } catch (error: any) {
      console.error("Signup error:", error);
      throw error;
    }
  };

  const logout = async () => {
    try {
      if (token) {
        await axios.post(`${API_BASE_URL}/logout`, {}, {
          headers: { Authorization: `Bearer ${token}` }
        });
      }
    } catch (error) {
      console.error("Logout error:", error);
    } finally {
      localStorage.removeItem("auth_token");
      localStorage.removeItem("user");
      setToken(null);
      setUser(null);
      delete axios.defaults.headers.common["Authorization"];
    }
  };

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, signup, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error("useAuth must be used within an AuthProvider");
  }
  return context;
}
