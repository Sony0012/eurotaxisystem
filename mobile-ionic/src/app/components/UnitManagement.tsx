import { Card, CardContent, CardHeader, CardTitle } from "./ui/card";
import { Badge } from "./ui/badge";
import { Button } from "./ui/button";
import { Input } from "./ui/input";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "./ui/dialog";
import { Label } from "./ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { Search, Plus, Car, MapPin, Video, CheckCircle, TrendingUp, Loader2 } from "lucide-react";
import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import api from "../services/api";
import { toast } from "sonner";

export function UnitManagement() {
  const [units, setUnits] = useState<any[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState("");
  const [addDialogOpen, setAddDialogOpen] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    fetchUnits();
  }, []);

  const fetchUnits = async () => {
    setIsLoading(true);
    try {
      const response = await api.get("/units");
      if (response.data.success) {
        setUnits(response.data.data);
      }
    } catch (error) {
      console.error("Error fetching units:", error);
      toast.error("Failed to load units.");
    } finally {
      setIsLoading(false);
    }
  };

  const filteredUnits = units.filter(
    (unit) =>
      unit.id.toLowerCase().includes(searchQuery.toLowerCase()) ||
      unit.plate_number.toLowerCase().includes(searchQuery.toLowerCase()) ||
      unit.model.toLowerCase().includes(searchQuery.toLowerCase()) ||
      unit.assigned_driver.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const getStatusColor = (status: string) => {
    switch (status?.toLowerCase()) {
      case "active":
        return "bg-green-100 text-green-800";
      case "under maintenance":
      case "maintenance":
        return "bg-orange-100 text-orange-800";
      case "coding":
        return "bg-blue-100 text-blue-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };

  const stats = {
    total: units.length,
    active: units.filter((u) => u.status?.toLowerCase() === "active").length,
    roiAchieved: units.filter((u) => u.roi).length,
    underMaintenance: units.filter((u) => u.status?.toLowerCase() === "maintenance" || u.status?.toLowerCase() === "under maintenance").length,
  };

  if (isLoading) {
    return (
      <div className="flex flex-col items-center justify-center min-h-[400px] gap-4">
        <Loader2 className="h-8 w-8 animate-spin text-yellow-500" />
        <p className="text-gray-500">Loading units...</p>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold">Unit Management</h2>
          <p className="text-sm text-gray-500 mt-1">Real-time status and ROI tracking</p>
        </div>
        <Button className="bg-yellow-400 hover:bg-yellow-500 text-gray-900" onClick={() => toast.info("Feature coming soon")}>
          <Plus className="mr-2 h-4 w-4" /> Add New Unit
        </Button>
      </div>

      <Card>
        <CardContent className="pt-6">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <Input
              type="text"
              placeholder="Search by unit, plate, model, or driver..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="pl-10"
            />
          </div>
        </CardContent>
      </Card>

      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        {[
          { label: "Total", value: stats.total, color: "text-gray-900" },
          { label: "Active", value: stats.active, color: "text-green-600" },
          { label: "ROI", value: stats.roiAchieved, color: "text-blue-600" },
          { label: "Mntnc", value: stats.underMaintenance, color: "text-orange-600" },
        ].map((s, i) => (
          <Card key={i}>
            <CardContent className="p-4 text-center">
              <div className={`text-xl font-bold ${s.color}`}>{s.value}</div>
              <p className="text-[10px] uppercase tracking-wider text-gray-500">{s.label}</p>
            </CardContent>
          </Card>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {filteredUnits.length > 0 ? (
          filteredUnits.map((unit) => (
            <Card key={unit.db_id} className="overflow-hidden border-none shadow-md">
              <CardHeader className="bg-gray-50/50 pb-4">
                <div className="flex items-start justify-between">
                  <div className="flex items-start gap-3">
                    <div className="p-2 bg-yellow-100 rounded-lg">
                      <Car className="h-5 w-5 text-yellow-700" />
                    </div>
                    <div>
                      <CardTitle className="text-lg">{unit.id}</CardTitle>
                      <p className="text-xs text-gray-500 uppercase font-semibold">
                        {unit.model} • {unit.plate_number}
                      </p>
                    </div>
                  </div>
                  <Badge className={getStatusColor(unit.status)}>{unit.status}</Badge>
                </div>
              </CardHeader>
              <CardContent className="pt-4 space-y-4">
                <div className="grid grid-cols-2 gap-4 text-sm">
                  <div>
                    <p className="text-gray-400 text-[10px] uppercase font-bold">Driver</p>
                    <p className="font-medium truncate">{unit.assigned_driver}</p>
                  </div>
                  <div className="text-right">
                    <p className="text-gray-400 text-[10px] uppercase font-bold">Type</p>
                    <p className="font-medium">{unit.type}</p>
                  </div>
                </div>

                <div className="bg-gray-50 p-3 rounded-xl space-y-3">
                  <div className="flex justify-between items-center text-sm">
                    <span className="text-gray-500">ROI Progress</span>
                    <span className="font-bold text-blue-600">{unit.roi_percentage}%</span>
                  </div>
                  <div className="w-full bg-gray-200 rounded-full h-1.5">
                    <div
                      className={`h-1.5 rounded-full ${unit.roi ? "bg-green-500" : "bg-blue-500"}`}
                      style={{ width: `${Math.min(unit.roi_percentage, 100)}%` }}
                    />
                  </div>
                  <div className="flex justify-between text-[10px] text-gray-500 uppercase font-bold">
                    <span>Revenue: ₱{unit.revenue.toLocaleString()}</span>
                    <span>Goal: ₱{(unit.purchase_cost + unit.maintenance_cost).toLocaleString()}</span>
                  </div>
                </div>

                <div className="flex gap-2">
                  <Button
                    variant="outline"
                    className="flex-1 text-xs h-9"
                    onClick={() => navigate(`/live-tracking/${unit.db_id}`)}
                  >
                    <MapPin className="h-3 w-3 mr-1" /> GPS
                  </Button>
                  <Button
                    variant="outline"
                    className="flex-1 text-xs h-9"
                    onClick={() => navigate(`/live-tracking/${unit.db_id}/dashcam`)}
                  >
                    <Video className="h-3 w-3 mr-1" /> Video
                  </Button>
                  <Button variant="secondary" className="flex-1 text-xs h-9">Details</Button>
                </div>
              </CardContent>
            </Card>
          ))
        ) : (
          <div className="col-span-full text-center py-10 text-gray-500 italic">
            No units found matching your search.
          </div>
        )}
      </div>
    </div>
  );
}
