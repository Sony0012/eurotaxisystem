import { Card, CardContent, CardHeader, CardTitle } from "./ui/card";
import { Badge } from "./ui/badge";
import { Button } from "./ui/button";
import { 
  Car, 
  Users, 
  TrendingUp, 
  AlertCircle, 
  CheckCircle, 
  Clock, 
  Calendar,
  ChevronRight,
  DollarSign,
  Activity,
  ArrowUpRight,
  ArrowDownRight,
  Loader2
} from "lucide-react";
import { motion } from "motion/react";
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import api from "../services/api";
import { toast } from "sonner";

export function Dashboard() {
  const navigate = useNavigate();
  const [stats, setStats] = useState<any>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    setIsLoading(true);
    try {
      const response = await api.get("/dashboard");
      if (response.data.success) {
        setStats(response.data.stats);
      }
    } catch (error) {
      console.error("Dashboard error:", error);
      toast.error("Failed to load dashboard statistics.");
    } finally {
      setIsLoading(false);
    }
  };

  if (isLoading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[400px] gap-4">
        <Loader2 className="h-8 w-8 animate-spin text-yellow-500" />
        <p className="text-gray-500">Syncing with server...</p>
      </div>
    );
  }

  const statCards = [
    {
      title: "Active Units",
      value: stats?.active_units || 0,
      icon: Car,
      color: "text-blue-600",
      bg: "bg-blue-50",
      trend: "+2 this week",
      trendColor: "text-green-600"
    },
    {
      title: "Active Drivers",
      value: stats?.active_drivers || 0,
      icon: Users,
      color: "text-purple-600",
      bg: "bg-purple-50",
      trend: "100% capacity",
      trendColor: "text-blue-600"
    },
    {
      title: "ROI Achieved",
      value: stats?.roi_achieved || 0,
      icon: TrendingUp,
      color: "text-green-600",
      bg: "bg-green-50",
      trend: "Units paid off",
      trendColor: "text-green-600"
    },
    {
      title: "Coding Today",
      value: stats?.coding_units || 0,
      icon: Clock,
      color: "text-orange-600",
      bg: "bg-orange-50",
      trend: "Suspended units",
      trendColor: "text-gray-500"
    }
  ];

  return (
    <div className="space-y-6 pb-10">
      {/* Header Section */}
      <div className="flex flex-col gap-1">
        <h2 className="text-2xl font-bold tracking-tight text-gray-900">Dashboard Overview</h2>
        <div className="flex items-center gap-2 text-sm text-gray-500">
          <Calendar className="h-4 w-4" />
          <span>{new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
        </div>
      </div>

      {/* Financial Summary Highlight */}
      <motion.div
        initial={{ opacity: 0, y: 10 }}
        animate={{ opacity: 1, y: 0 }}
        className="bg-gradient-to-br from-[#1e3a8a] to-[#0c1437] rounded-3xl p-6 text-white shadow-xl relative overflow-hidden"
      >
        <div className="relative z-10">
          <div className="flex justify-between items-start mb-4">
            <div>
              <p className="text-blue-200 text-xs font-bold uppercase tracking-wider mb-1">Today's Net Income</p>
              <h3 className="text-4xl font-extrabold">₱{(stats?.net_income || 0).toLocaleString()}</h3>
            </div>
            <div className="p-3 bg-white/10 rounded-2xl backdrop-blur-md">
              <DollarSign className="h-6 w-6 text-yellow-400" />
            </div>
          </div>
          
          <div className="grid grid-cols-2 gap-4 mt-6">
            <div className="bg-white/5 p-3 rounded-2xl">
              <p className="text-blue-200 text-[10px] uppercase font-bold mb-1">Gross Boundary</p>
              <div className="flex items-center gap-1">
                <span className="font-bold">₱{(stats?.today_boundary || 0).toLocaleString()}</span>
                <ArrowUpRight className="h-3 w-3 text-green-400" />
              </div>
            </div>
            <div className="bg-white/5 p-3 rounded-2xl">
              <p className="text-blue-200 text-[10px] uppercase font-bold mb-1">Expenses</p>
              <div className="flex items-center gap-1">
                <span className="font-bold">₱{(stats?.today_expenses || 0).toLocaleString()}</span>
                <ArrowDownRight className="h-3 w-3 text-red-400" />
              </div>
            </div>
          </div>
        </div>
        {/* Background Decorative Element */}
        <div className="absolute -bottom-10 -right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl" />
      </motion.div>

      {/* Quick Stats Grid */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {statCards.map((stat, i) => (
          <motion.div
            key={stat.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: i * 0.1 }}
          >
            <Card className="border-none shadow-sm hover:shadow-md transition-shadow h-full overflow-hidden">
              <CardContent className="p-4">
                <div className={`p-2 w-10 h-10 rounded-xl ${stat.bg} ${stat.color} mb-3 flex items-center justify-center`}>
                  <stat.icon className="h-5 w-5" />
                </div>
                <p className="text-xs font-medium text-gray-500 mb-1">{stat.title}</p>
                <div className="text-2xl font-bold text-gray-900">{stat.value}</div>
                <p className={`text-[10px] mt-1 font-bold ${stat.trendColor}`}>{stat.trend}</p>
              </CardContent>
            </Card>
          </motion.div>
        ))}
      </div>

      {/* Maintenance & Activity Section */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <Card className="border-none shadow-sm">
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-bold">Maintenance Status</CardTitle>
            <Activity className="h-4 w-4 text-orange-500" />
          </CardHeader>
          <CardContent>
            <div className="flex items-center justify-between mb-4">
              <div className="space-y-1">
                <p className="text-2xl font-bold">{stats?.maintenance_units || 0}</p>
                <p className="text-xs text-gray-500">Units in garage</p>
              </div>
              <Badge variant="outline" className="bg-orange-50 text-orange-700 border-orange-200">
                Action Required
              </Badge>
            </div>
            <Button 
              variant="outline" 
              className="w-full text-xs"
              onClick={() => navigate("/maintenance")}
            >
              Manage Maintenance <ChevronRight className="ml-1 h-3 w-3" />
            </Button>
          </CardContent>
        </Card>

        <Card className="border-none shadow-sm bg-yellow-50/50">
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-bold">System Status</CardTitle>
            <Shield className="h-4 w-4 text-green-600" />
          </CardHeader>
          <CardContent>
            <div className="flex items-center gap-3 p-3 bg-white rounded-xl mb-3 shadow-sm">
              <div className="h-2 w-2 rounded-full bg-green-500 animate-pulse" />
              <div className="flex-1">
                <p className="text-xs font-bold text-gray-900">Live Server Connected</p>
                <p className="text-[10px] text-gray-500">All services are operational</p>
              </div>
            </div>
            <p className="text-[10px] text-gray-400 italic">Last sync: {new Date().toLocaleTimeString()}</p>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
