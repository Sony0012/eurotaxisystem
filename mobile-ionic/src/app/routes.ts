import { createBrowserRouter } from "react-router-dom";
import { Layout } from "./components/Layout";
import { Dashboard } from "./components/Dashboard";
import { UnitManagement } from "./components/UnitManagement";
import { BoundaryManagement } from "./components/BoundaryManagement";
import { Maintenance } from "./components/Maintenance";
import { DriverManagement } from "./components/DriverManagement";
import { DriverBehavior } from "./components/DriverBehavior";
import { OfficeExpenses } from "./components/OfficeExpenses";
import { Analytics } from "./components/Analytics";
import { Login } from "./components/Login";
import { Signup } from "./components/Signup";
import { About } from "./components/About";
import { ForgotPassword } from "./components/ForgotPassword";
import { OTPVerificationStandalone } from "./components/OTPVerificationStandalone";
import { ResetPassword } from "./components/ResetPassword";
import { LiveTracking } from "./components/LiveTracking";
import { UnitTracking } from "./components/UnitTracking";
import { DashcamViewer } from "./components/DashcamViewer";

export const router = createBrowserRouter([
  {
    path: "/login",
    Component: Login,
  },
  {
    path: "/forgot-password",
    Component: ForgotPassword,
  },
  {
    path: "/verify-otp",
    Component: OTPVerificationStandalone,
  },
  {
    path: "/reset-password",
    Component: ResetPassword,
  },
  {
    path: "/about",
    Component: About,
  },
  {
    path: "/",
    Component: Layout,
    children: [
      { index: true, Component: Dashboard },
      { path: "units", Component: UnitManagement },
      { path: "boundaries", Component: BoundaryManagement },
      { path: "maintenance", Component: Maintenance },
      { path: "drivers", Component: DriverManagement },
      { path: "driver-behavior", Component: DriverBehavior },
      { path: "office-expenses", Component: OfficeExpenses },
      { path: "analytics", Component: Analytics },
      { path: "live-tracking", Component: LiveTracking },
      { path: "live-tracking/:unitId", Component: UnitTracking },
      { path: "live-tracking/:unitId/dashcam", Component: DashcamViewer },
    ],
  },
]);
