import { useState, useEffect } from "react";
import { MapContainer, TileLayer, Marker, Popup, useMap } from "react-leaflet";
import { Icon, divIcon } from "leaflet";
import "leaflet/dist/leaflet.css";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "./ui/card";
import { Badge } from "./ui/badge";
import { Input } from "./ui/input";
import { Button } from "./ui/button";
import { 
  Car, 
  Search, 
  Filter, 
  MapPin, 
  Navigation, 
  Clock,
  Video,
  RefreshCw
} from "lucide-react";
import { mockGpsData, simulateGpsUpdate, getStatusColor, getStatusLabel, type GpsLocation } from "../utils/mockGpsData";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "./ui/select";
import { useNavigate } from "react-router-dom";

// Fix for default marker icon in Leaflet
const createCustomIcon = (color: string, plateNumber: string) => {
  return divIcon({
    className: 'custom-div-icon',
    html: `
      <div style="position: relative;">
        <div style="
          background-color: ${color};
          width: 32px;
          height: 32px;
          border-radius: 50% 50% 50% 0;
          position: relative;
          transform: rotate(-45deg);
          border: 3px solid white;
          box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        ">
          <div style="
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            color: white;
            font-weight: bold;
            font-size: 14px;
          ">🚕</div>
        </div>
        <div style="
          position: absolute;
          top: 35px;
          left: 50%;
          transform: translateX(-50%);
          background: white;
          padding: 2px 6px;
          border-radius: 4px;
          white-space: nowrap;
          font-size: 10px;
          font-weight: bold;
          box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        ">${plateNumber}</div>
      </div>
    `,
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
  });
};

function MapUpdater({ center }: { center: [number, number] }) {
  const map = useMap();
  useEffect(() => {
    map.setView(center, map.getZoom());
  }, [center, map]);
  return null;
}

export function LiveTracking() {
  const navigate = useNavigate();
  const [locations, setLocations] = useState<GpsLocation[]>(mockGpsData);
  const [searchTerm, setSearchTerm] = useState("");
  const [statusFilter, setStatusFilter] = useState<string>("all");
  const [selectedUnit, setSelectedUnit] = useState<GpsLocation | null>(null);
  const [mapCenter, setMapCenter] = useState<[number, number]>([14.5547, 121.0244]);
  const [isAutoRefresh, setIsAutoRefresh] = useState(true);

  // Simulate real-time updates
  useEffect(() => {
    if (!isAutoRefresh) return;

    const interval = setInterval(() => {
      setLocations(prevLocations =>
        prevLocations.map(loc => 
          loc.status === "active" ? simulateGpsUpdate(loc) : loc
        )
      );
    }, 5000); // Update every 5 seconds

    return () => clearInterval(interval);
  }, [isAutoRefresh]);

  // Filter units
  const filteredLocations = locations.filter(loc => {
    const matchesSearch = 
      loc.plateNumber.toLowerCase().includes(searchTerm.toLowerCase()) ||
      loc.driver?.toLowerCase().includes(searchTerm.toLowerCase()) ||
      loc.address?.toLowerCase().includes(searchTerm.toLowerCase());
    
    const matchesStatus = statusFilter === "all" || loc.status === statusFilter;

    return matchesSearch && matchesStatus;
  });

  // Statistics
  const stats = {
    total: locations.length,
    active: locations.filter(l => l.status === "active").length,
    idle: locations.filter(l => l.status === "idle").length,
    offline: locations.filter(l => l.status === "offline").length,
    avgSpeed: Math.round(
      locations.filter(l => l.status === "active").reduce((sum, l) => sum + l.speed, 0) / 
      locations.filter(l => l.status === "active").length || 0
    )
  };

  const handleUnitClick = (location: GpsLocation) => {
    setSelectedUnit(location);
    setMapCenter([location.latitude, location.longitude]);
  };

  const viewUnitDetails = (unitId: string) => {
    navigate(`/live-tracking/${unitId}`);
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-3xl">Live Fleet Tracking</h2>
          <p className="text-gray-600">Real-time GPS monitoring of all taxi units</p>
        </div>
        <Button
          variant={isAutoRefresh ? "default" : "outline"}
          onClick={() => setIsAutoRefresh(!isAutoRefresh)}
          className={isAutoRefresh ? "bg-green-600 hover:bg-green-700" : ""}
        >
          <RefreshCw className={`h-4 w-4 mr-2 ${isAutoRefresh ? "animate-spin" : ""}`} />
          {isAutoRefresh ? "Auto-Refresh ON" : "Auto-Refresh OFF"}
        </Button>
      </div>

      {/* Statistics Cards */}
      <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>Total Units</CardDescription>
            <CardTitle className="text-3xl">{stats.total}</CardTitle>
          </CardHeader>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>Active</CardDescription>
            <CardTitle className="text-3xl text-green-600">{stats.active}</CardTitle>
          </CardHeader>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>Idle</CardDescription>
            <CardTitle className="text-3xl text-yellow-600">{stats.idle}</CardTitle>
          </CardHeader>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>Offline</CardDescription>
            <CardTitle className="text-3xl text-red-600">{stats.offline}</CardTitle>
          </CardHeader>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardDescription>Avg Speed</CardDescription>
            <CardTitle className="text-3xl">{stats.avgSpeed} <span className="text-base">km/h</span></CardTitle>
          </CardHeader>
        </Card>
      </div>

      {/* Filters and Search */}
      <div className="flex flex-col sm:flex-row gap-4">
        <div className="flex-1 relative">
          <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
          <Input
            placeholder="Search by plate number, driver, or location..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="pl-10"
          />
        </div>
        <Select value={statusFilter} onValueChange={setStatusFilter}>
          <SelectTrigger className="w-full sm:w-48">
            <Filter className="h-4 w-4 mr-2" />
            <SelectValue placeholder="Filter by status" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">All Status</SelectItem>
            <SelectItem value="active">Active</SelectItem>
            <SelectItem value="idle">Idle</SelectItem>
            <SelectItem value="offline">Offline</SelectItem>
          </SelectContent>
        </Select>
      </div>

      {/* Map and Unit List */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Map */}
        <Card className="lg:col-span-2">
          <CardHeader>
            <CardTitle>Live Map View</CardTitle>
            <CardDescription>
              {filteredLocations.length} unit(s) displayed on map
            </CardDescription>
          </CardHeader>
          <CardContent className="p-0">
            <div className="h-[600px] w-full relative">
              <MapContainer
                center={mapCenter}
                zoom={12}
                style={{ height: "100%", width: "100%" }}
                className="rounded-b-lg"
              >
                <TileLayer
                  attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                  url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
                />
                <MapUpdater center={mapCenter} />
                {filteredLocations.map((location) => (
                  <Marker
                    key={location.unitId}
                    position={[location.latitude, location.longitude]}
                    icon={createCustomIcon(getStatusColor(location.status), location.plateNumber)}
                    eventHandlers={{
                      click: () => handleUnitClick(location)
                    }}
                  >
                    <Popup>
                      <div className="space-y-2 min-w-[200px]">
                        <div className="flex items-center justify-between">
                          <h3 className="font-bold text-lg">{location.plateNumber}</h3>
                          <Badge variant={
                            location.status === "active" ? "default" :
                            location.status === "idle" ? "secondary" : "destructive"
                          }>
                            {getStatusLabel(location.status)}
                          </Badge>
                        </div>
                        {location.driver && (
                          <p className="text-sm"><strong>Driver:</strong> {location.driver}</p>
                        )}
                        <p className="text-sm"><strong>Speed:</strong> {location.speed} km/h</p>
                        <p className="text-sm"><strong>Location:</strong> {location.address}</p>
                        <p className="text-xs text-gray-500">
                          Last update: {location.lastUpdate.toLocaleTimeString()}
                        </p>
                        <Button 
                          size="sm" 
                          className="w-full mt-2"
                          onClick={() => viewUnitDetails(location.unitId)}
                        >
                          View Details
                        </Button>
                      </div>
                    </Popup>
                  </Marker>
                ))}
              </MapContainer>
            </div>
          </CardContent>
        </Card>

        {/* Unit List */}
        <Card>
          <CardHeader>
            <CardTitle>Unit List</CardTitle>
            <CardDescription>Click to center on map</CardDescription>
          </CardHeader>
          <CardContent className="p-0">
            <div className="max-h-[600px] overflow-y-auto">
              {filteredLocations.map((location) => (
                <div
                  key={location.unitId}
                  className={`p-4 border-b hover:bg-gray-50 cursor-pointer transition-colors ${
                    selectedUnit?.unitId === location.unitId ? "bg-yellow-50" : ""
                  }`}
                  onClick={() => handleUnitClick(location)}
                >
                  <div className="flex items-start justify-between mb-2">
                    <div className="flex items-center space-x-2">
                      <Car className="h-5 w-5 text-gray-600" />
                      <span className="font-semibold">{location.plateNumber}</span>
                    </div>
                    <Badge variant={
                      location.status === "active" ? "default" :
                      location.status === "idle" ? "secondary" : "destructive"
                    }>
                      {getStatusLabel(location.status)}
                    </Badge>
                  </div>
                  {location.driver && (
                    <p className="text-sm text-gray-600 mb-1">{location.driver}</p>
                  )}
                  <div className="flex items-center space-x-4 text-xs text-gray-500 mb-2">
                    <span className="flex items-center">
                      <Navigation className="h-3 w-3 mr-1" />
                      {location.speed} km/h
                    </span>
                    <span className="flex items-center">
                      <Clock className="h-3 w-3 mr-1" />
                      {new Date(location.lastUpdate).toLocaleTimeString()}
                    </span>
                  </div>
                  <p className="text-xs text-gray-500 mb-2">
                    <MapPin className="h-3 w-3 inline mr-1" />
                    {location.address}
                  </p>
                  <div className="flex gap-2">
                    <Button
                      size="sm"
                      variant="outline"
                      className="flex-1"
                      onClick={(e) => {
                        e.stopPropagation();
                        viewUnitDetails(location.unitId);
                      }}
                    >
                      <MapPin className="h-3 w-3 mr-1" />
                      Track
                    </Button>
                    <Button
                      size="sm"
                      variant="outline"
                      className="flex-1"
                      onClick={(e) => {
                        e.stopPropagation();
                        navigate(`/live-tracking/${location.unitId}/dashcam`);
                      }}
                    >
                      <Video className="h-3 w-3 mr-1" />
                      Dashcam
                    </Button>
                  </div>
                </div>
              ))}
              {filteredLocations.length === 0 && (
                <div className="p-8 text-center text-gray-500">
                  <Car className="h-12 w-12 mx-auto mb-2 opacity-50" />
                  <p>No units found</p>
                </div>
              )}
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
