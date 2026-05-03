import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { ArrowLeft, Car, Users, Calendar, TrendingUp, Wrench, MapPin, Video, Loader2, RefreshCw, RotateCcw } from "lucide-react";
import api from "../services/api";
import { toast } from "sonner";

const fmt = (n: any) => "₱" + Number(n || 0).toLocaleString("en-PH", { minimumFractionDigits: 2 });
const fmtDate = (d: any) => d ? new Date(d).toLocaleDateString("en-PH", { month: "short", day: "2-digit", year: "numeric" }) : "N/A";

const TABS = ["Overview","Drivers","Coding","Boundary","Maintenance","ROI","Location","Dashcam"];
const CODING_SCHEDULE: Record<string,string> = { Monday:"1, 2", Tuesday:"3, 4", Wednesday:"5, 6", Thursday:"7, 8", Friday:"9, 0" };

function StatusPill({ status }: { status: string }) {
  const s = status?.toLowerCase();
  const cfg: any = {
    active: "bg-green-100 text-green-700 border-green-200",
    maintenance: "bg-red-100 text-red-700 border-red-200",
    coding: "bg-yellow-100 text-yellow-700 border-yellow-200",
    at_risk: "bg-orange-100 text-orange-700 border-orange-200",
  };
  return <span className={`px-2.5 py-1 text-[10px] font-black uppercase rounded-full border ${cfg[s] || "bg-gray-100 text-gray-600 border-gray-200"}`}>{status}</span>;
}

function InfoRow({ label, value }: { label: string; value: any }) {
  return (
    <div className="flex justify-between items-center py-2.5 border-b border-gray-50 last:border-0">
      <span className="text-xs font-bold text-gray-400 uppercase tracking-tight">{label}</span>
      <span className="font-black text-gray-800 text-right text-sm">{value ?? "N/A"}</span>
    </div>
  );
}

function HealthBar({ unit }: { unit: any }) {
  if (!unit?.gps_device_count && !unit?.imei) return null;
  const km = Math.max(0, (unit.current_gps_odo || 0) - (unit.last_service_odo_gps || 0));
  const pct = Math.min(100, Math.round((km / 5000) * 100));
  const over = km >= 5000;
  const bar = over ? "bg-red-600" : pct >= 85 ? "bg-orange-500" : pct >= 60 ? "bg-yellow-400" : "bg-green-500";
  const txt = over ? "text-red-600" : pct >= 85 ? "text-orange-600" : pct >= 60 ? "text-yellow-600" : "text-green-600";
  const lbl = over ? "⚠ SERVICE OVERDUE" : pct >= 85 ? "Service Due Soon" : pct >= 60 ? "Maintenance Progress" : "Optimal Health";
  return (
    <div className="bg-white border border-gray-100 rounded-2xl p-4 mt-4">
      <div className="flex justify-between mb-2">
        <span className={`text-[10px] font-black uppercase tracking-wider ${txt}`}>{lbl}</span>
        <span className="text-[10px] text-gray-400 font-bold">{Number(km).toLocaleString()} / 5,000 KM</span>
      </div>
      <div className="h-2.5 bg-gray-100 rounded-full overflow-hidden">
        <div className={`h-full ${bar} rounded-full transition-all`} style={{ width: `${pct}%` }} />
      </div>
      {over && <p className="text-[10px] text-red-500 mt-1 italic">Exceeded by {Number(km - 5000).toLocaleString()}km.</p>}
    </div>
  );
}

export function UnitDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [unit, setUnit] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState("Overview");

  useEffect(() => {
    (async () => {
      setLoading(true);
      try {
        const res = await api.get(`/units/${id}`);
        setUnit(res.data.data ?? res.data);
      } catch (e: any) {
        toast.error(e.response?.data?.message || "Failed to load unit details.");
      } finally { setLoading(false); }
    })();
  }, [id]);

  if (loading) return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-50 gap-3">
      <Loader2 className="w-8 h-8 animate-spin text-blue-600" />
      <p className="text-sm text-gray-500 font-medium">Loading unit details...</p>
    </div>
  );
  if (!unit) return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-50 gap-3 px-6 text-center">
      <Car className="w-16 h-16 text-gray-200" />
      <p className="text-base font-black text-gray-800">Unit not found</p>
      <button onClick={() => navigate("/units")} className="px-6 py-2.5 bg-blue-600 text-white font-black text-sm rounded-xl">Go Back</button>
    </div>
  );

  const driverCount = (unit.primary_driver ? 1 : 0) + (unit.secondary_driver ? 1 : 0);
  const driversFull = driverCount >= 2;
  const lastDigit = (unit.plate_number || "").slice(-1);
  const codingDay = unit.coding_day || "N/A";
  const today = new Date().toLocaleString("en-US", { weekday: "long" });

  return (
    <div className="flex flex-col min-h-full bg-gray-50">
      {/* Back Button */}
      <div className="bg-white px-4 pt-4 pb-2 flex items-center gap-3 border-b border-gray-100">
        <button onClick={() => navigate("/units")} className="p-2 bg-gray-100 rounded-xl active:bg-gray-200">
          <ArrowLeft className="w-4 h-4 text-gray-600" />
        </button>
        <div>
          <p className="text-xs text-gray-400 font-bold uppercase tracking-widest">Unit Details</p>
          <p className="text-base font-black text-gray-900 leading-tight">Complete unit information</p>
        </div>
      </div>

      {/* Hero Header — matches web dark card */}
      <div className="bg-gradient-to-r from-slate-800 to-blue-900 mx-4 mt-4 rounded-2xl p-4 shadow-lg">
        <div className="flex justify-between items-start mb-3">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
              <Car className="w-6 h-6 text-white" />
            </div>
            <div>
              <p className="text-xl font-black text-white tracking-wider">{unit.plate_number}</p>
              <p className="text-blue-200 text-xs font-bold">{unit.make} {unit.model} ({unit.year})</p>
            </div>
          </div>
          <div className="text-right">
            <p className="text-xl font-black text-white">{fmt(unit.boundary_rate)}</p>
            <p className="text-blue-200 text-[10px] font-bold uppercase tracking-wider">Daily Boundary Rate</p>
          </div>
        </div>
        <div className="flex items-center gap-2">
          <span className="px-2.5 py-1 bg-white/20 text-white text-[10px] font-black rounded-full uppercase">{unit.status}</span>
          <span className="px-2.5 py-1 bg-white/20 text-white text-[10px] font-black rounded-full uppercase">{unit.unit_type || "Standard"}</span>
        </div>
      </div>

      {/* Tab Bar — scrollable */}
      <div className="overflow-x-auto scrollbar-hide bg-white mt-4 border-b border-gray-200">
        <div className="flex min-w-max">
          {TABS.map(t => (
            <button key={t} onClick={() => setActiveTab(t)}
              className={`px-4 py-3 text-[10px] font-black uppercase tracking-wider border-b-2 transition-all whitespace-nowrap ${
                activeTab === t ? "border-blue-600 text-blue-600" : "border-transparent text-gray-400"
              }`}>{t}</button>
          ))}
        </div>
      </div>

      {/* Tab Content */}
      <div className="flex-1 overflow-y-auto px-4 py-4">
        {/* ── OVERVIEW ── */}
        {activeTab === "Overview" && (
          <div className="space-y-4">
            {/* Quick Stats */}
            <div className="grid grid-cols-2 gap-3">
              {[
                { icon: <Users className="w-5 h-5 text-blue-600" />, bg: "bg-blue-50", label: "Drivers", val: `${driverCount}/2` },
                { icon: <Calendar className="w-5 h-5 text-green-600" />, bg: "bg-green-50", label: "Next Coding", val: `${unit.days_until_coding ?? "?"}d` },
                { icon: <TrendingUp className="w-5 h-5 text-purple-600" />, bg: "bg-purple-50", label: "ROI", val: `${Number(unit.roi_percentage || 0).toFixed(1)}%` },
                { icon: <Wrench className="w-5 h-5 text-orange-600" />, bg: "bg-orange-50", label: "Maint Jobs", val: unit.maintenance_count ?? 0 },
              ].map((s, i) => (
                <div key={i} className="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm flex items-center gap-3">
                  <div className={`p-2.5 ${s.bg} rounded-xl`}>{s.icon}</div>
                  <div>
                    <p className="text-[9px] text-gray-400 uppercase font-black tracking-widest">{s.label}</p>
                    <p className="text-xl font-black text-gray-900">{s.val}</p>
                  </div>
                </div>
              ))}
            </div>

            {/* Basic Information */}
            <div className="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
              <p className="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-3 flex items-center gap-2">ℹ Basic Information</p>
              <InfoRow label="Plate Number" value={<span className="bg-gray-100 px-2 py-0.5 rounded font-black">{unit.plate_number}</span>} />
              <InfoRow label="Vehicle" value={`${unit.make} ${unit.model}`} />
              <InfoRow label="Year" value={unit.year} />
              <InfoRow label="Motor No." value={unit.motor_no} />
              <InfoRow label="Chassis No." value={unit.chassis_no} />
              <InfoRow label="Status" value={<StatusPill status={unit.status} />} />
              <InfoRow label="Unit Type" value={<span className="uppercase font-black">{unit.unit_type}</span>} />
              <div className="pt-3 mt-2 border-t border-gray-100 flex justify-between">
                <div>
                  <p className="text-[9px] text-gray-400 uppercase font-black mb-0.5">Created</p>
                  <p className="text-xs font-bold text-gray-600">{fmtDate(unit.created_at)}</p>
                </div>
                <div className="text-right">
                  <p className="text-[9px] text-gray-400 uppercase font-black mb-0.5">Updated</p>
                  <p className="text-xs font-bold text-gray-600">{fmtDate(unit.updated_at)}</p>
                </div>
              </div>
              <div className="pt-3 mt-2 border-t border-gray-100 flex justify-between items-center">
                <span className="text-xs font-black text-gray-900 uppercase tracking-widest">Boundary Rate</span>
                <span className="text-xl font-black text-blue-600">{fmt(unit.boundary_rate)}</span>
              </div>
            </div>

            {/* Driver Assignment */}
            <div className="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
              <p className="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-3 flex items-center gap-2">👥 Driver Assignment</p>
              <div className="flex justify-between items-center mb-2">
                <span className="text-xs font-bold text-gray-400 uppercase">Drivers</span>
                <span className="font-black text-gray-900">{driverCount}/2</span>
              </div>
              <div className="flex justify-between items-center mb-4">
                <span className="text-xs font-bold text-gray-400 uppercase">Status</span>
                <span className={`px-2.5 py-1 text-[10px] font-black rounded-full border ${driversFull ? "bg-red-50 text-red-600 border-red-200" : "bg-green-50 text-green-600 border-green-200"}`}>
                  {driversFull ? "Full" : "Available"}
                </span>
              </div>
              {[unit.primary_driver, unit.secondary_driver].filter(Boolean).map((d: any, i: number) => (
                <div key={i} className="bg-gray-50 p-3 rounded-xl border border-gray-100 mb-2">
                  <div className="flex justify-between items-start mb-1">
                    <p className="text-sm font-black text-gray-900">{d.full_name}</p>
                    <span className="text-[9px] font-black bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded uppercase">Active</span>
                  </div>
                  <p className="text-[10px] text-gray-400">{d === unit.primary_driver ? "Primary Driver" : "Secondary Driver"}</p>
                </div>
              ))}
              {driverCount === 0 && (
                <div className="text-center py-6">
                  <p className="text-xs font-bold text-gray-300 uppercase tracking-widest">No Drivers Assigned</p>
                </div>
              )}
            </div>

            <HealthBar unit={unit} />
          </div>
        )}

        {/* ── DRIVERS ── */}
        {activeTab === "Drivers" && (
          <div className="space-y-6">
            <div className="flex items-center gap-2 mb-2">
              <div className="p-2 bg-blue-50 rounded-lg">
                <Users className="w-5 h-5 text-blue-600" />
              </div>
              <h3 className="text-sm font-black text-gray-900 uppercase tracking-widest">Assigned Drivers Details</h3>
            </div>

            {[
              { label: "Primary Driver", data: unit.primary_driver },
              { label: "Secondary Driver", data: unit.secondary_driver },
            ].map((d, i) => (
              <div key={i} className="bg-white border border-gray-100 rounded-3xl p-5 shadow-sm overflow-hidden relative">
                <div className="flex justify-between items-start mb-4">
                  <div>
                    <p className="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">{d.label}</p>
                    <h4 className={`text-lg font-black ${d.data?.full_name ? "text-gray-900" : "text-gray-300 italic"}`}>
                      {d.data?.full_name || "Unassigned"}
                    </h4>
                    {d.data && (
                      <p className="text-[11px] text-gray-500 font-medium mt-1">
                        License: {d.data.license_number} <span className="mx-1 text-gray-300">|</span> Contact: {d.data.contact_number}
                      </p>
                    )}
                  </div>
                  {d.data && (
                    <span className="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full border border-green-100 uppercase">
                      Active
                    </span>
                  )}
                </div>

                {d.data ? (
                  <div className="grid grid-cols-2 gap-y-4 gap-x-4 mt-6 pt-6 border-t border-gray-50">
                    <div>
                      <p className="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">License Number</p>
                      <p className="text-sm font-black text-gray-900">{d.data.license_number}</p>
                    </div>
                    <div>
                      <p className="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Contact</p>
                      <p className="text-sm font-black text-gray-900">{d.data.contact_number}</p>
                    </div>
                    <div>
                      <p className="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Daily Target</p>
                      <p className="text-sm font-black text-gray-900">{fmt(d.data.daily_boundary_target)}</p>
                    </div>
                    <div>
                      <p className="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Hire Date</p>
                      <p className="text-sm font-black text-gray-900">{fmtDate(d.data.hire_date)}</p>
                    </div>
                    <div className="col-span-2">
                      <p className="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">License Expiry</p>
                      <p className={`text-sm font-black ${new Date(d.data.license_expiry) < new Date() ? "text-red-600" : "text-gray-900"}`}>
                        {fmtDate(d.data.license_expiry)}
                      </p>
                    </div>
                  </div>
                ) : (
                  <div className="py-8 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200 mt-4">
                    <Users className="w-10 h-10 text-gray-200 mx-auto mb-2" />
                    <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver Slot Available</p>
                  </div>
                )}
              </div>
            ))}
          </div>
        )}

        {/* ── CODING ── */}
        {activeTab === "Coding" && (
          <div className="space-y-6">
            <div className="flex items-center gap-2 mb-2">
              <div className="p-2 bg-blue-50 rounded-lg">
                <Calendar className="w-5 h-5 text-blue-600" />
              </div>
              <h3 className="text-sm font-black text-gray-900 uppercase tracking-widest">MMDA Coding Schedule</h3>
            </div>

            <div className="grid grid-cols-1 gap-6">
              {/* Current Unit Status */}
              <div className="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 pb-2 border-b border-gray-50">Current Unit Status</p>
                <div className="space-y-5">
                  <div className="flex justify-between items-center">
                    <span className="text-[11px] font-black text-gray-400 uppercase tracking-tight">Coding Day</span>
                    <span className="px-3 py-1 bg-blue-600 text-white rounded-full text-[10px] font-black uppercase tracking-wider">{codingDay}</span>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-[11px] font-black text-gray-400 uppercase tracking-tight">Plate Ending</span>
                    <span className="text-lg font-black text-gray-900">{lastDigit}</span>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-[11px] font-black text-gray-400 uppercase tracking-tight">Next Schedule</span>
                    <span className="text-sm font-black text-gray-900">{unit.next_coding_date}</span>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-[11px] font-black text-gray-400 uppercase tracking-tight">Remaining</span>
                    <span className={`text-lg font-black ${unit.days_until_coding === 0 ? "text-red-600" : "text-green-600"}`}>
                      {unit.days_until_coding === 0 ? "Today" : `${unit.days_until_coding} Days`}
                    </span>
                  </div>
                </div>
              </div>

              {/* Standard MMDA Reference */}
              <div className="bg-white border border-gray-100 rounded-3xl p-6 shadow-sm">
                <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 pb-2 border-b border-gray-50">Standard MMDA Reference</p>
                <div className="space-y-1">
                  {Object.entries(CODING_SCHEDULE).map(([day, digits]) => (
                    <div key={day} className={`flex justify-between items-center p-3 rounded-2xl ${today === day ? "bg-blue-50/50" : ""}`}>
                      <span className={`text-[11px] font-black uppercase tracking-tight ${today === day ? "text-blue-600" : "text-gray-500"}`}>{day}</span>
                      <span className={`text-base font-black ${today === day ? "text-blue-600" : "text-gray-900"}`}>{digits}</span>
                    </div>
                  ))}
                </div>
                <div className="mt-6 pt-4 border-t border-gray-50">
                  <div className="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">
                    <div className="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse" />
                    Coding Time: 7:00 AM – 10:00 AM
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* ── BOUNDARY ── */}
        {activeTab === "Boundary" && (
          <div className="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div className="px-4 py-3 border-b border-gray-100">
              <p className="text-sm font-black text-gray-900 uppercase tracking-widest">Boundary Collection History</p>
            </div>
            {unit.boundary_history?.length > 0 ? (
              <div className="divide-y divide-gray-50">
                {unit.boundary_history.map((b: any, i: number) => (
                  <div key={i} className="px-4 py-3 flex justify-between items-center">
                    <div>
                      <p className="text-xs font-black text-gray-800">{fmtDate(b.date)}</p>
                      <p className="text-[10px] text-gray-400 font-bold">{b.full_name || "N/A"}</p>
                    </div>
                    <div className="text-right">
                      <p className="text-sm font-black text-green-600">{fmt(b.actual_boundary)}</p>
                      <p className="text-[9px] text-gray-400 uppercase font-bold">{b.status}</p>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="py-16 text-center">
                <p className="text-xs font-bold text-gray-300 uppercase tracking-widest">No boundary history found</p>
              </div>
            )}
          </div>
        )}

        {/* ── MAINTENANCE ── */}
        {activeTab === "Maintenance" && (
          <div className="space-y-4">
            {unit.maintenance_records?.length > 0 ? unit.maintenance_records.map((m: any, i: number) => (
              <div key={i} className={`bg-white border-l-4 ${m.status === "completed" ? "border-l-green-500" : "border-l-yellow-500"} rounded-2xl p-4 shadow-sm`}>
                <div className="flex justify-between items-start mb-3">
                  <div>
                    <p className="text-sm font-black text-gray-900 uppercase">{m.maintenance_type || "Maintenance"}</p>
                    <p className="text-[10px] text-gray-400 mt-0.5">Started: {fmtDate(m.date_started)}{m.date_completed ? ` · Done: ${fmtDate(m.date_completed)}` : ""}</p>
                  </div>
                  <div className="text-right">
                    <span className={`text-[9px] font-black px-2 py-0.5 rounded-full ${m.status === "completed" ? "bg-green-100 text-green-700" : "bg-yellow-100 text-yellow-700"}`}>{m.status}</span>
                    <p className="text-base font-black text-red-600 mt-1">{fmt(m.cost)}</p>
                  </div>
                </div>
                {m.description && <div className="bg-gray-50 p-3 rounded-xl mb-2"><p className="text-[9px] font-black text-gray-400 uppercase mb-1">Work Description</p><p className="text-xs text-gray-700">{m.description}</p></div>}
                {m.mechanic_name && <div className="bg-gray-50 p-3 rounded-xl"><p className="text-[9px] font-black text-gray-400 uppercase mb-1">Mechanic</p><p className="text-xs font-bold text-gray-700">{m.mechanic_name}</p></div>}
              </div>
            )) : (
              <div className="bg-white border border-gray-100 rounded-2xl py-16 text-center shadow-sm">
                <Wrench className="w-12 h-12 text-gray-200 mx-auto mb-3" />
                <p className="text-xs font-bold text-gray-400 uppercase tracking-widest">No Maintenance Records</p>
              </div>
            )}
          </div>
        )}

        {/* ── ROI ── */}
        {activeTab === "ROI" && (
          <div className="space-y-4">
            <div className="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl p-5 text-white">
              <p className="text-sm font-black uppercase tracking-widest mb-4">ROI Analysis</p>
              {[
                { label: "Total Investment", val: fmt(unit.roi?.total_investment) },
                { label: "Total Revenue", val: fmt(unit.roi?.total_revenue) },
                { label: "Total Expenses", val: fmt(unit.roi?.total_expenses) },
              ].map((r, i) => (
                <div key={i} className="mb-3">
                  <p className="text-purple-200 text-[10px] font-bold uppercase">{r.label}</p>
                  <p className="text-2xl font-black">{r.val}</p>
                </div>
              ))}
            </div>
            <div className="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
              <p className="text-sm font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 pb-3 mb-3">ROI Metrics</p>
              <InfoRow label="ROI %" value={<span className={`font-black ${(unit.roi?.roi_percentage || 0) > 0 ? "text-green-600" : "text-red-600"}`}>{Number(unit.roi?.roi_percentage || 0).toFixed(1)}%</span>} />
              <InfoRow label="Payback Period" value={`${Number(unit.roi?.payback_period || 0).toFixed(1)} months`} />
              <InfoRow label="Monthly Revenue" value={fmt(unit.roi?.monthly_revenue)} />
              <InfoRow label="Monthly Expenses" value={fmt(unit.roi?.monthly_expenses)} />
              <div className="mt-3 pt-3 border-t border-gray-50">
                <div className="flex justify-between text-[10px] font-bold text-gray-400 uppercase mb-1">
                  <span>ROI Achievement</span><span>{Number(unit.roi?.roi_percentage || 0).toFixed(1)}%</span>
                </div>
                <div className="h-3 bg-gray-100 rounded-full overflow-hidden">
                  <div className="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full" style={{ width: `${Math.min(100, Math.max(0, unit.roi?.roi_percentage || 0))}%` }} />
                </div>
              </div>
            </div>
          </div>
        )}

        {/* ── LOCATION ── */}
        {activeTab === "Location" && (
          <div className="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <div className="flex justify-between items-center mb-4">
              <p className="text-sm font-black text-gray-900 uppercase tracking-widest">Real-Time Location</p>
            </div>
            <div className="grid grid-cols-2 gap-3 mb-4">
              {[
                { label: "GPS Status", val: unit.imei ? "Active" : "No GPS" },
                { label: "Speed", val: `${unit.gps_speed || 0} KM/H` },
                { label: "Engine", val: unit.gps_ignition === 1 ? "ON" : "OFF" },
                { label: "Last Sync", val: unit.last_location_update ? fmtDate(unit.last_location_update) : "N/A" },
              ].map((g, i) => (
                <div key={i} className="bg-gray-50 p-3 rounded-xl border border-gray-100">
                  <p className="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">{g.label}</p>
                  <p className="text-sm font-black text-gray-800">{g.val}</p>
                </div>
              ))}
            </div>
            <div className="bg-gray-50 rounded-2xl h-40 flex items-center justify-center border border-gray-100">
              <div className="text-center">
                <MapPin className="w-8 h-8 text-gray-300 mx-auto mb-2" />
                <p className="text-xs font-bold text-gray-400">{unit.current_location || "Location unavailable"}</p>
                {unit.latitude && unit.longitude && (
                  <p className="text-[10px] text-gray-300 font-mono mt-1">{unit.latitude}, {unit.longitude}</p>
                )}
              </div>
            </div>
          </div>
        )}

        {/* ── DASHCAM ── */}
        {activeTab === "Dashcam" && (
          <div className="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm">
            <p className="text-sm font-black text-gray-900 uppercase tracking-widest border-b border-gray-100 pb-3 mb-4">Dashcam Information</p>
            <InfoRow label="Dashcam" value={<span className={`px-2 py-0.5 text-[10px] font-black rounded-full ${unit.dashcam_enabled ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"}`}>{unit.dashcam_enabled ? "Enabled" : "Disabled"}</span>} />
            <div className="mt-4 bg-gray-50 rounded-2xl h-32 flex items-center justify-center border border-gray-100">
              <div className="text-center">
                <Video className="w-8 h-8 text-gray-300 mx-auto mb-2" />
                <p className="text-xs font-bold text-gray-400">Video integration coming soon</p>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
